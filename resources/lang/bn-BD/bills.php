<?php

return [

    'bill_number'           => 'বিল নম্বর',
    'bill_date'             => 'বিলের তারিখ',
    'bill_amount'           => 'বিলের পরিমাণ',
    'total_price'           => 'মোট মূল্য',
    'due_date'              => 'নির্দিষ্ট তারিখ',
    'order_number'          => 'অর্ডার নম্বর',
    'bill_from'             => 'বিল প্রেরক',

    'quantity'              => 'পরিমাণ',
    'price'                 => 'মূল্য',
    'sub_total'             => 'সাবটোটাল',
    'discount'              => 'ছাড়',
    'item_discount'         => 'লাইন ছাড়',
    'tax_total'             => 'মোট কর',
    'total'                 => 'সর্বমোট',

    'item_name'             => 'আইটেমের নাম|আইটেমের নামসমূহ',
    'recurring_bills'       => 'আবর্তিত বিল|আবর্তিত বিলসমূহ',

    'show_discount'         => 'ছাড় :discount%',
    'add_discount'          => 'ছাড় যোগ করুন',
    'discount_desc'         => 'সাবটোটাল থেকে',

    'payment_made'          => 'প্রদত্ত পেমেন্ট',
    'payment_due'           => 'বাকি পেমেন্ট',
    'amount_due'            => 'বাকি পরিমাণ',
    'paid'                  => 'পরিশোধিত',
    'histories'             => 'ইতিহাস',
    'payments'              => 'পেমেন্টসমূহ',
    'add_payment'           => 'পেমেন্ট যোগ করুন',
    'mark_paid'             => 'পরিশোধিত হিসেবে চিহ্নিত করুন',
    'mark_received'         => 'গৃহীত হিসেবে চিহ্নিত করুন',
    'mark_cancelled'        => 'বাতিল হিসেবে চিহ্নিত করুন',
    'download_pdf'          => 'PDF ডাউনলোড করুন',
    'send_mail'             => 'ই-মেইল পাঠান',
    'create_bill'           => 'বিল তৈরি করুন',
    'receive_bill'          => 'বিল গ্রহণ করুন',
    'make_payment'          => 'পেমেন্ট করুন',

    'form_description' => [
        'billing'           => 'বিলের বিবরণ আপনার বিলে প্রদর্শিত হবে। ড্যাশবোর্ড ও প্রতিবেদনে বিলের তারিখ ব্যবহার করা হয়। আপনি যে তারিখে পরিশোধ আশা করেন সেটিকে নির্দিষ্ট তারিখ হিসেবে নির্বাচন করুন।',
    ],

    'messages' => [
        'draft'             => 'এটি একটি <b>খসড়া</b> বিল এবং গৃহীত হওয়ার পর চার্টে প্রদর্শিত হবে।',

        'status' => [
            'created'       => 'তৈরি করা হয়েছে :date',
            'receive' => [
                'draft'     => 'প্রেরিত নয়',
                'received'  => 'গৃহীত হয়েছে :date',
            ],
            'paid' => [
                'await'     => 'পেমেন্টের অপেক্ষায়',
            ],
        ],
    ],

];
