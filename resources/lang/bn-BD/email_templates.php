<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} ইনভয়েস তৈরি করা হয়েছে',
        'body'          => 'সুপ্রিয় {customer_name},<br /><br /> আমরা আপনার সুবিধার্থে এই ইনভয়েসটি তৈরি করেছিঃ <strong>{invoice_number}</strong>.<br /><br /> ইনভয়েসের বিস্তারিত বিবরণ দেখতে এবং পে-মেন্ট করতে নিচের লিংকে ক্লিক করুনঃ <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> কোন জিজ্ঞাসা থাকলে নিঃসংকোচে যোগাযোগ করুন । <br /><br />ধন্যবাদান্তে,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} সময়োত্তীর্ণ ইনভয়েসের বিজ্ঞপ্তি',
        'body'          => 'প্রিয় {customer_name},<br /><br />এটি ইনভয়েস নম্বর: <strong>{invoice_number}</strong> এর সময় উত্তীর্ণ হওয়ার নোটিশ।<br /><br />ইনভয়েসের সর্বমোট মূল্য {invoice_total} এবং সময়সীমা <strong>{invoice_due_date}</strong> পর্যন্ত ছিল।<br /><br />ইনভয়েসের বিস্তারিত দেখতে এবং পেমেন্ট করতে এই লিংক ভিজিট করুন: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ধন্যবাদান্তে,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} ‌ইনভয়েস সময়োত্তীর্ণের বিজ্ঞপ্তি',
        'body'          => 'প্রিয়,<br /><br />{customer_name} একটি বকেয়া মেয়াদোত্তীর্ণের বিজ্ঞপ্তি পেয়েছেন <strong>{invoice_number}</strong> ইনভয়েসের জন্য ।<br /><br />ইনভয়েসের মোট পরিমাণ ছিল {invoice_total} এবং এটি পরিশোধের তারিখ ছিল <strong>{invoice_due_date}</strong>.<br /><br /> নিচের লিংক থেকে আপনি ইনভয়েসের বিস্তারিত  বিবরণ দেখতে পাবেনঃ<a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />ধন্যবাদান্তে,<br />{company_name}।',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} আবর্তক ইনভয়েস তৈরি করা হয়েছে',
        'body'          => 'প্রিয়  {customer_name},<br /><br />আপনার আবর্তক চক্রের ভিত্তিতে আমরা আপনার জন্য নিম্নোক্ত ইনভয়েসটি প্রস্তুত করেছিঃ <strong>{invoice_number}</strong>.<br /><br />ইনভয়েসের বিস্তারিত বিবরণ দেখতে এবং পরিশোধ করতে নিচের লিংকে ক্লিক করুনঃ <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />কোন প্রশ্ন বা জিজ্ঞাসা থাকলে নিঃসংকোচে যোগাযোগ করুন ।<br /><br />ধন্যবাদান্তে,<br />{company_name}।',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} আবর্তক ইনভয়েস তৈরি করা হয়েছে',
        'body'          => 'প্রিয়<br /><br />{customer_name}  আবর্তক চক্রের ভিত্তিতে <strong>{invoice_number}</strong> স্বয়ংক্রিয়ভাবে ইনভয়েসটি তৈরি করা হয়েছে। <br /><br />নিচের লিংকে ইনভয়েসের বিস্তারিত বিবরণ পাবেনঃ <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br /> ধন্যবাদান্তে,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => ' {invoice_number} ইনভয়েসের জন্য পেমেন্ট গৃহীত হয়েছে',
        'body'          => 'প্রিয় {customer_name},<br /><br /> পেমেন্ট করার জন্য আপনাকে ধন্যবাদ। পেমেন্টের বিস্তারিত বিবরণ নিচে উল্ল্যেখ করা হলোঃ  <br /><br />-------------------------------------------------<br />পরিমাণঃ <strong>{transaction_total}</strong><br />তারিখঃ <strong>{transaction_paid_date}</strong><br />ইনভয়েস নম্বরঃ <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br /> নিচের লিংক থেকে আপনি যখন খুশি ইনভয়েসের বিস্তারিত বিবরণ দেখতে পাবেনঃ<a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> কোন প্রশ্ন থাকলে নিঃসংকোচে যোগাযোগ করুন।<br /><br />শুভ কামনায়,<br />{company_name}।',
    ],

    'invoice_payment_admin' => [
        'subject'       => '{invoice_number} ইনভয়েসের জন্য পেমেন্ট গৃহীত হয়েছে',
        'body'          => 'প্রিয়,<br /><br />{customer_name} <strong>{invoice_number}</strong> ইনভয়েসের জন্য একটি পেমেন্ট নথিভুক্ত করেছেন<br /><br />ইনভয়েসের বিস্তারিত বিবরণ নিচের লিংকে পাবেনঃ <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />ধন্যবাদান্তে,<br />{company_name}।',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} বিল অনুস্মারক বিজ্ঞপ্তি',
        'body'          => 'প্রিয় ,<br /><br />এটি  {vendor_name} কে প্রদত্ত <strong>{bill_number}</strong> বিল পরিশোধের অনুস্মারক বিজ্ঞপ্তি।<br /><br />মোট বিল {bill_total} এবং পরিশোধের তারিখ <strong>{bill_due_date}</strong>। <br /><br />নিচের লিংক থেকে আপনি বিলের বিশদ বিবরণ দেখতে পাবেনঃ <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />ধন্যবাদান্তে,<br />{company_name}।',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} আবর্তক বিল তৈরি করা হয়েছে',
        'body'          => 'সুস্বাগতম,<br /><br /> {vendor_name} আবর্তক চক্রের ভিত্তিতে , <strong>{bill_number}</strong> ইনভয়েসটি স্বয়ংক্রিয়ভাবে তৈরি করা হয়েছে ।<br /><br /> বিলের বিস্তারিত বিবরণ নিচের লিংকে পাবেনঃ <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />ধন্যবাদান্তে,<br />{company_name}',
    ],

];
