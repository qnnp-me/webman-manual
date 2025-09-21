<?php

/**
 * 脚本功能：遍历 `resource/doc/zh-cn` 下的所有 Markdown 文件，将文内的相对链接与图片地址
 * 转换为站点的绝对 URL。
 *
 * 工作原理：
 * - 将形如 [文本](path/to/page.md#anchor) 的相对链接改写为 BASE_URL + 路径(.html) + 锚点；
 * - 将形如 ![alt](path/to/img.png) 的相对图片地址改写为 BASE_IMG_URL + 路径；
 * - 仅处理以 .md 结尾的链接与非 http/data 开头的图片地址；其余保持不变；
 * - 就地覆盖写回原 Markdown 文件，并在控制台输出统计信息。
 *
 * 使用方法（Windows PowerShell）：
 * 1) 在项目根目录执行：
 *    php script\\transfer-link.php
 *
 * 可配置项：
 * - BASE_URL：文档页面的域名与路径前缀（用于普通链接）。
 * - BASE_IMG_URL：图片资源的域名与路径前缀（用于图片）。
 * - ROOT_DIR：要处理的 Markdown 根目录，默认 `resource/doc/zh-cn`。
 *
 * 注意：
 * - 脚本会直接改写文件，建议先做好版本管理或备份。
 * - 如需处理其它语言目录，修改 ROOT_DIR 或调用 convertMarkdownLinks(自定义路径)。
 */

// 基础URL前缀
const BASE_URL = 'https://www.workerman.net/doc/webman/';
const BASE_IMG_URL = 'https://www.workerman.net/doc/';
// 项目根目录
const ROOT_DIR = __DIR__ . '/../resource/doc/zh-cn';

/**
 * 将相对路径的markdown链接和图像链接转为绝对URL
 */
function convertMarkdownLinks(string $rootDir = ROOT_DIR)
{
    // 获取所有markdown文件
    $files = getMarkdownFiles($rootDir);
    
    echo "找到 " . count($files) . " 个markdown文件\n";
    
    $totalLinks = 0;
    $totalImages = 0;
    
    foreach ($files as $file) {
        // 获取文件内容
        $content = file_get_contents($file);
        
        // 获取相对于根目录的相对路径
        $relativePath = str_replace($rootDir, '', $file);
        // 获取文件所在目录
        $currentDir = dirname($relativePath);
        if ($currentDir === '.') {
            $currentDir = '';
        }
        
        $modifiedContent = $content;
        $linkCount = 0;
        $imageCount = 0;
        
        // 1. 处理正常链接 [text](url)
        preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $linkText = $match[1];
            $linkUrl = $match[2];
            
            // 只处理.md结尾的相对链接
            if (preg_match('/\.md(#.*)?$/', $linkUrl)) {
                // 是否是相对路径
                if (strpos($linkUrl, '/') === 0) {
                    // 以/开头的是相对于网站根目录
                    $linkUrl = ltrim($linkUrl, '/');
                } elseif (strpos($linkUrl, 'http') !== 0) {
                    // 相对路径，需要结合当前文件路径
                    $linkUrl = normalizePath($currentDir . '/' . $linkUrl);
                }
                
                // 去除锚点部分
                $anchor = '';
                if (strpos($linkUrl, '#') !== false) {
                    list($linkUrl, $anchor) = explode('#', $linkUrl, 2);
                    $anchor = '#' . $anchor;
                }
                
                // 将.md转换为.html
                $linkUrl = str_replace('.md', '.html', $linkUrl);
                
                // 构建完整URL
                $fullUrl = BASE_URL . $linkUrl . $anchor;
                
                // 替换原始链接
                $modifiedContent = str_replace(
                    $match[0],
                    '[' . $linkText . '](' . $fullUrl . ')',
                    $modifiedContent
                );
                
                $linkCount++;
            }
        }
        
        // 2. 处理图像链接 ![alt](img_url)
        preg_match_all('/!\[([^\]]*)\]\(([^)]+)\)/', $modifiedContent, $imgMatches, PREG_SET_ORDER);
        
        foreach ($imgMatches as $match) {
            $imgAlt = $match[1];
            $imgUrl = $match[2];
            
            // 只处理相对路径的图片
            if (strpos($imgUrl, 'http') !== 0 && strpos($imgUrl, 'data:') !== 0) {
                // 是否是相对路径
                if (strpos($imgUrl, '/') === 0) {
                    // 以/开头的是相对于网站根目录
                    $imgUrl = ltrim($imgUrl, '/');
                } else {
                    // 相对路径，需要结合当前文件路径
                    $imgUrl = normalizePath($currentDir . '/' . $imgUrl);
                }
                
                // 构建完整URL (使用@前缀，按照要求)
                $fullImgUrl = BASE_IMG_URL . $imgUrl;
                
                // 替换原始图像链接
                $modifiedContent = str_replace(
                    $match[0],
                    '![' . $imgAlt . '](' . $fullImgUrl . ')',
                    $modifiedContent
                );
                
                $imageCount++;
            }
        }
        
        // 如果有修改，写回文件
        if ($modifiedContent !== $content) {
            file_put_contents($file, $modifiedContent);
            echo "处理文件: $relativePath - 转换了 $linkCount 个链接和 $imageCount 个图像\n";
            $totalLinks += $linkCount;
            $totalImages += $imageCount;
        }
    }
    
    echo "完成转换，共处理 $totalLinks 个链接和 $totalImages 个图像\n";
}

/**
 * 递归获取所有markdown文件
 */
function getMarkdownFiles(string $dir): array
{
    $files = [];
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $path = $dir . $item;
        
        if (is_dir($path)) {
            $files = array_merge($files, getMarkdownFiles($path . DIRECTORY_SEPARATOR));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'md') {
            $files[] = $path;
        }
    }
    
    return $files;
}

/**
 * 规范化路径，处理 ../ 和 ./
 */
function normalizePath(string $path): string
{
    // 将路径拆分为数组
    $parts = explode('/', $path);
    $result = [];
    
    foreach ($parts as $part) {
        if ($part === '' || $part === '.') {
            continue;
        }
        
        if ($part === '..') {
            // 回到上一级目录
            array_pop($result);
        } else {
            $result[] = $part;
        }
    }
    
    return implode('/', $result);
}

// 执行转换
convertMarkdownLinks();

echo "Markdown链接和图像转换完成!\n";