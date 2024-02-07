# নিরাপত্তা

## ব্যবহারকারী চালিয়ে নেওয়া
সুনির্দিষ্টভাবে nginx চালিত ব্যবহারকারীর সাথে চালিত ব্যবহারকারী হিসেবে চালিয়ে নেওয়া সুপরিসর্বিত হওয়ার জন্য পরামর্শ দেয়া হয়। চালিয়ে নেওয়া ব্যবহারকারীটি `config/server.php` ফাইলের`user` এবং`group` এবং `config/process.php` ফাইলের `user` এবং`group` এর মাধ্যমে সেট করা হয়ে থাকে। যেমন, মনিটর প্রক্রিয়াকে চালিয়ে নেওয়া না করার পরামর্শ দেওয়া হয় কারণ এটা সাধারণভাবে মান নিয়ে কাজ করতে হয়।

## নিয়মিতকারী নিয়মসূচি
`controller` ডিরেক্টরির মধ্যে বা উপ-ডিরেক্টরিতে কেবলমাত্র নিয়োজিত কন্ট্রোলার ফাইল রাখা যাবে, অন্যান্য ক্লাস ফাইল না রাখা যাবে, অন্যথায়, [Controller সাফফিক্স](https://www.workerman.net/doc/webman/controller.html#%E0%A6%95%E0%A6%A8%E0%A7%8D%E0%A6%9F%E0%A7%8D%E0%A6%B0%E0%A6%B2%E0%A6%BE%E0%A6%B0-%E0%A6%B8%E0%A6%AB%E0%A6%BF%E0%A6%95%E0%A7%8D%E0%A6%B8) চালু না করার সময়, অবৈধ URL অ্যাক্সেসের দ্বারা ক্লাস ফাইল পথনির্ভর অভিবাসন ঘটায়, যা অনিয়ন্ত্রিত পরিণাম সৃষ্টি করতে পারে। উদাহরণস্বরূপ, `app/controller/model/User.php` বাস্তবিকে একটি মডেল ক্লাস হওয়া যাোয়, তারপর এটি ভুলভাবে `controller` নির্ধারণ করা হয়েছে, [Controller সাফফিক্স](https://www.workerman.net/doc/webman/controller.html#%E0%A6%95%E0%A6%A8%E0%A7%8D%E0%A6%9F%E0%A7%8D%E0%A6%B0%E0%A6%B2%E0%A6%BE%E0%A6%B0-%E0%A6%B8%E0%A6%AB%E0%A6%BF%E0%A6%95%E0%A7%8D%E0%A6%B8) চালু না করার সময়, ব্যবহারকারী ব্যাবসায়িকভাবে `/model/user/xxx` এর মাধ্যমে `User.php` এর যেকোন পদক্ষেপ অ্যাক্সেস করতে পারে। এই ধরনের অবস্থা সম্পূর্ণ বাতিল করার জন্য, মনোন্মুখীদন[Controller সাফফিক্স](https://www.workerman.net/doc/webman/controller.html#%E0%A6%95%E0%A6%A8%E0%A7%8D%E0%A6%9F%E0%A7%8D%E0%A6%B0%E0%A6%B2%E0%A6%BE%E0%A6%B0-%E0%A6%B8%E0%A6%AB%E0%A6%BF%E0%A6%95%E0%A7%8D%E0%A6%B8) ব্যবহারে কোন মামুলি কন্ট্রোলার ফাইল কোন কন্ট্রোলার ফাইল হয় এটা নির্দেশিত করা হয়।

## XSS ফিল্টার
সাধারণভাবে মনে রাখা হয়, webman অনুরোধটি XSS এর জন্য পরিবর্তনশীল নয়। ওয়েভেম্যান এক্সএসএস সেইবারে অ্যাক্সেসের সময় এক্সএসএস ফিল্টারিং করার পরামর্শ দেয়। এবং টুইগ, ব্লেড, থিং-টেম্পলেট এবং অন্যান্য টেম্পলেট স্বয়ংক্রিয়ভাবে XSS ফিল্টার অনুষ্ঠান করে, কোনও ম্যানুয়াল ফিল্টার প্রয়োজন নেই, খুব সুন্দর।

> **পরামর্শ**
> যদি আপনি ডাটাবেজে প্রবেশের আগে আপনার XSS ফিল্টার করেন, তবে সে বিনামূল্যে কিছু অ্যাপ্লিকেশন প্লাগইনের অসামঞ্জাস্যমূলক সমস্যা সৃষ্টি করতে পারে।

## SQL ইনজেকশন প্রতিরোধ
SQL ইনজেকশন এৰ প্রতিরক্ষা কৰিবলৈ ORM ব্যৱহাৰ কৰালৈ পূৰ্বেই recursive-uniter, think-orm ইত্যাদি ব্যৱহাৰ কৰক। সম্ভাৱনা দৃশ্যমান সময়ত নাই নিজৰা SQL সংমিলন কৰিব নোৱান৷

## nginx প্রক্সি
আপোনাৰ অ্যাপ বাহ্যিক ইউজাৰসমূহক প্ৰদর্শন কৰাত প্ৰয়াব হাব৷ প্ৰায় nginx প্ৰক্সিৰ পৰা আগৰিয়াই webman এটা যোগ কৰিব পৰাক৷ এনেকাৱ প্ৰৱেশ ভাঙনী কাৰণে সুৰক্ষা প্ৰকাৰ সাধাৰণ কৰিব৷ বিৱৰণ বিস্তৃত জননে [nginx প্ৰক্সি](nginx-proxy.md)ৰ বাবে দেখোন৷