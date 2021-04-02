<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} ინვოისი შეიქმნა',
        'body'          => 'ძვირფასო {customer_name},<br /><br />თქვენთვის შემდეგი ინვოისი მოვამზადეთ: <strong>{invoice_number}</strong>.<br /><br />შეგიძლიათ იხილოთ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ნუ მოგერიდებათ დაგვიკავშირდეთ ნებისმიერი შეკითხვისათვის.<br /><br />Საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} ინვოისის ვადაგადაცილების შეტყობინება',
        'body'          => 'ძვირფასო {customer_name},<br /><br />ეს ვადაგასული შეტყობინებაა: <strong>{invoice_number}</strong>ინვოისი.<br /><br />ინვოისის მთლიანი თანხა {invoice_total} უნდა იყოს <strong>{invoice_due_date}</strong>.<br /><br />შეგიძლიათ იხილოთ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} ინვოისის ვადაგადაცილების შეტყობინება',
        'body'          => 'გამარჯობათ,<br /><br />{customer_name} მიიღო ვადაგადაცილებული ცნობა <strong>{invoice_number}</strong> invoice.<br /><br />ინვოისის მთლიანი თანხა {invoice_total} უნდა იყოს <strong>{invoice_due_date}</strong>.<br /><br />Yშეგიძლიათ იხილოთ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} განმეორებადი ინვოისი შეიქმნა',
        'body'          => 'ძვირფასო {customer_name},<br /><br />თქვენი განმეორებითი წრის საფუძველზე, ჩვენ მოამზადეთ შემდეგი ინვოისი თქვენთვის: <strong>{invoice_number}</strong>.<br /><br />შეგიძლიათ იხილოთ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ნუ მოგერიდებათ, დაგვიკავშირდით ნებისმიერ კითხვაზე<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} განმეორებადი ინვოისი შეიქმნა',
        'body'          => 'გამარჯობათ,<br /><br /> დაფუძნებული {customer_name} განმეორებადი წრე, <strong>{invoice_number}</strong> ინვოისი ავტომატურად შეიქმნა.<br /><br />შეგიძლიათ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'გადახდა მიღებულია {invoice_number} ინვოისი',
        'body'          => 'ძვირფასო {customer_name},<br /><br />გმადლობთ გადახდისთვის. იხილეთ გადახდის დეტალები ქვემოთ:<br /><br />-------------------------------------------------<br /><br />თანხა: <strong>{transaction_total}<br /></strong>თარიღი: <strong>{transaction_paid_date}</strong><br />ინვოისის ნომერი: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />ყოველთვის შეგიძლიათ ნახოთ ინვოისის დეტალები შემდეგ ბმულზე: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ნი მოგერიდებათ, დაგვიკავშირდით ნებისმიერ კითხვაზე.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'გადახდა მიღებულია {invoice_number} ინვოისი',
        'body'          => 'განარჯობათ,<br /><br />{customer_name} ჩაიწერა გადასახადი<strong>{invoice_number}</strong> ინვოისი.<br /><br />შეგიძლიათ იხილოთ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} ანგარიშის შეხსენება',
        'body'          => 'გამარჯობათ,<br /><br />ეს არის შეხსენება <strong>{bill_number}</strong> გადამხდელი {vendor_name}.<br /><br />აგარიშის მთლიანი თანხა {bill_total} უნდა იყოს <strong>{bill_due_date}</strong>.<br /><br />შეგიძლიათ იხილოთ ინვოისის დეტალები და განაგრძოთ გადახდა შემდეგი ბმულიდან: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} განმეორებადი ანგარიში შეიქმნა',
        'body'          => 'გამარჯობათ,<br /><br /> დაფიზნებულია {vendor_name} განმეორებად წრეზე, <strong>{bill_number}</strong> ინვოისი შეიქმნა აუტომატურად.<br /><br />შეგიძლიათ იხილოთk: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />საუკეთესო სურვილებით,<br />{company_name}',
    ],

];
