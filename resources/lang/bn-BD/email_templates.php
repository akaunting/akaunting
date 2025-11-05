<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} ইনভয়েস তৈরি হয়েছে',
        'body'          => 'প্রিয় {customer_name},<br /><br />আমরা আপনার জন্য নিম্নোক্ত ইনভয়েসটি প্রস্তুত করেছি: <strong>{invoice_number}</strong>।<br /><br />নিচের লিংক থেকে ইনভয়েসের বিস্তারিত দেখে পেমেন্ট করতে পারেন: <a href="{invoice_guest_link}">{invoice_number}</a>।<br /><br />যে কোনও প্রশ্ন থাকলে নির্দ্বিধায় যোগাযোগ করুন।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} ইনভয়েস সময়োত্তীর্ণ নোটিশ',
        'body'          => 'প্রিয় {customer_name},<br /><br />এটি <strong>{invoice_number}</strong> ইনভয়েসের সময়োত্তীর্ণ নোটিশ।<br /><br />ইনভয়েসের মোট পরিমাণ {invoice_total} এবং নির্দিষ্ট তারিখ ছিল <strong>{invoice_due_date}</strong>।<br /><br />নিচের লিংক থেকে ইনভয়েসের বিস্তারিত দেখে পেমেন্ট করতে পারেন: <a href="{invoice_guest_link}">{invoice_number}</a>।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} ইনভয়েস সময়োত্তীর্ণ নোটিশ',
        'body'          => 'শুভেচ্ছা,<br /><br />{customer_name} <strong>{invoice_number}</strong> ইনভয়েসের জন্য একটি সময়োত্তীর্ণ নোটিশ পেয়েছেন।<br /><br />ইনভয়েসের মোট পরিমাণ {invoice_total} এবং নির্দিষ্ট তারিখ ছিল <strong>{invoice_due_date}</strong>।<br /><br />নিচের লিংক থেকে ইনভয়েসের বিস্তারিত দেখতে পারেন: <a href="{invoice_admin_link}">{invoice_number}</a>।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} আবর্তক ইনভয়েস তৈরি হয়েছে',
        'body'          => 'প্রিয় {customer_name},<br /><br />আপনার আবর্তক চক্রের ভিত্তিতে আমরা নিম্নোক্ত ইনভয়েসটি প্রস্তুত করেছি: <strong>{invoice_number}</strong>।<br /><br />নিচের লিংক থেকে ইনভয়েসের বিস্তারিত দেখে পেমেন্ট করতে পারেন: <a href="{invoice_guest_link}">{invoice_number}</a>।<br /><br />যে কোনো প্রশ্নে নির্দ্বিধায় যোগাযোগ করুন।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} আবর্তক ইনভয়েস তৈরি হয়েছে',
        'body'          => 'শুভেচ্ছা,<br /><br />{customer_name}-এর আবর্তক চক্রের ভিত্তিতে <strong>{invoice_number}</strong> ইনভয়েসটি স্বয়ংক্রিয়ভাবে তৈরি হয়েছে।<br /><br />নিচের লিংক থেকে ইনভয়েসের বিস্তারিত দেখতে পারেন: <a href="{invoice_admin_link}">{invoice_number}</a>।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '{invoice_number} ইনভয়েসের জন্য পেমেন্ট গৃহীত হয়েছে',
        'body'          => 'প্রিয় {customer_name},<br /><br />পেমেন্টের জন্য ধন্যবাদ। নিচে পেমেন্টের বিবরণ দেওয়া হলো:<br /><br />-------------------------------------------------<br />পরিমাণ: <strong>{transaction_total}</strong><br />তারিখ: <strong>{transaction_paid_date}</strong><br />ইনভয়েস নম্বর: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />নিচের লিংক থেকে যেকোনো সময় ইনভয়েসের বিস্তারিত দেখতে পারেন: <a href="{invoice_guest_link}">{invoice_number}</a>।<br /><br />যে কোনো প্রশ্নে নির্দ্বিধায় যোগাযোগ করুন।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '{invoice_number} ইনভয়েসের জন্য পেমেন্ট গৃহীত হয়েছে',
        'body'          => 'শুভেচ্ছা,<br /><br />{customer_name} <strong>{invoice_number}</strong> ইনভয়েসের জন্য একটি পেমেন্ট রেকর্ড করেছেন।<br /><br />ইনভয়েসের বিস্তারিত নিচের লিংক থেকে দেখতে পারেন: <a href="{invoice_admin_link}">{invoice_number}</a>।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} বিল অনুস্মারক',
        'body'          => 'শুভেচ্ছা,<br /><br />এটি {vendor_name}-এর <strong>{bill_number}</strong> বিলের একটি অনুস্মারক।<br /><br />বিলের মোট পরিমাণ {bill_total} এবং নির্দিষ্ট তারিখ <strong>{bill_due_date}</strong>।<br /><br />নিচের লিংক থেকে বিলের বিস্তারিত দেখতে পারেন: <a href="{bill_admin_link}">{bill_number}</a>।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} আবর্তক বিল তৈরি হয়েছে',
        'body'          => 'শুভেচ্ছা,<br /><br />{vendor_name}-এর আবর্তক চক্রের ভিত্তিতে <strong>{bill_number}</strong> বিলটি স্বয়ংক্রিয়ভাবে তৈরি হয়েছে।<br /><br />নিচের লিংক থেকে বিলের বিস্তারিত দেখতে পারেন: <a href="{bill_admin_link}">{bill_number}</a>।<br /><br />শুভেচ্ছান্তে,<br />{company_name}',
    ],

];
