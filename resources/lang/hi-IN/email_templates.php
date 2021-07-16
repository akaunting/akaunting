<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} चालान बनाया गया',
        'body'          => 'प्रिय {customer_name}, <br/> <br/> हमने आपके लिए निम्नलिखित चालान तैयार किया हैं: <strong> {invoice_number} </strong>। <br/> <br/> आप चालान का विवरण देख सकते हैं और निम्नलिखित लिंक से भुगतान के साथ आगे बढ़ सकते हैं:<a href="{invoice_guest_link}"> {invoice_number} </a>। <br/> किसी भी प्रश्न के लिए हमसे संपर्क करने में संकोच न करें। <br/> <br/> सादर, <br/> {company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} चालान समय पर भुगतान नहीं किये जानें पर सूचना',
        'body'          => 'प्रिय {customer_name},<br/><br/>यह <strong>{invoice_number}</strong> चालान के लिए भुगतान ना किये जाने पर सूचना है। <br/><br/>चालान कुल {invoice_total} है और नियत तारीख <strong>{invoice_due_date}</strong> है।<br/><br/>आप चालान का विवरण देख सकते हैं और निम्नलिखित लिंक से भुगतान के साथ आगे बढ़ सकते हैं: <a href="{invoice_guest_link}">{invoice_number}</a>।<br/><br/>सादर,<br>{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} चालान समय पर भुगतान नहीं किये जानें पर सूचना',
        'body'          => 'नमस्ते,<br/><br/>{customer_name} को <strong>{invoice_number}</strong> चालान के लिए भुगतान ना किये जानें पर सूचना मिली है।<br/><br/>चालान कुल {invoice_total} है और नियत तारीख <strong>{invoice_due_date}</strong> है।<br/><br/>आप निम्न लिंक से चालान का विवरण देख सकते हैं: <a href="{invoice_admin_link}">{invoice_number}</a>।<br/><br/>सादर,<br/>{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} आवर्ती चालान बनाया गया',
        'body'          => 'प्रिय {customer_name},<br/><br/>आपके आवर्ती सर्कल के आधार पर, हमने आपके लिए निम्नलिखित चालान तैयार किया है: <strong>{invoice_number}</strong>।<br/><br/> आप चालान का विवरण देख सकते हैं और निम्नलिखित लिंक से भुगतान के साथ आगे बढ़ सकते हैं: <a href="{invoice_guest_link}">{invoice_number}</a>।<br/><br/>किसी भी प्रश्न के लिए हमसे संपर्क करने में संकोच न करें।<br/><br/> सादर,<br>{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} आवर्ती चालान बनाया गया',
        'body'          => 'नमस्ते,<br/><br/>{customer_name} के आवर्ती सर्कल के आधार पर, <strong>{invoice_number}</strong> चालान स्वचालित रूप से बनाया गया है।<br/><br/>आप निम्न लिंक से चालान का विवरण देख सकते हैं: <a href="{invoice_admin_link}">{invoice_number}</a>।<br/><br/>सादर,<br/>{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '{invoice_number} चालान के लिए भुगतान प्राप्त हुआ',
        'body'          => 'प्रिय {customer_name},<br/><br/>भुगतान के लिए धन्यवाद।भुगतान का विवरण देखें:<br/><br/>-------------------------------------------------<br/><br/>राशि: <strong>{transaction_total}<br /></strong>तारीख: <strong>{transaction_paid_date}</strong><br/>चालान संख्या: <strong>{invoice_number}<br/><br/></strong>-------------------------------------------------<br/><br/>आप निम्न लिंक से कभी भी चालान का विवरण देख सकते हैं: <a href="{invoice_guest_link}">{invoice_number}</a>।<br/><br/>किसी भी प्रश्न के लिए हमसे संपर्क करने में संकोच न करें।<br/><br/>सादर,<br/>{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '{invoice_number} चालान के लिए भुगतान प्राप्त हुआ',
        'body'          => 'नमस्ते,<br/><br/>{customer_name} ने <strong>{invoice_number}</strong> चालान का भुगतान दर्ज किया।<br/><br/>आप निम्न लिंक से चालान का विवरण देख सकते हैं: <a href="{invoice_admin_link}">{invoice_number}</a>।<br/><br/>सादर,<br/>{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} बिल याद दिलाने वाली सूचना',
        'body'          => 'नमस्ते,<br/><br/>यह {vendor_name} के लिए <strong>{bill_number}</strong> बिल की याद दिलाने वाली सूचना है।<br/><br/>बिल कुल {bill_total} है और और नियत तारीख <strong>{bill_due_date}</strong> है।<br/><br/>आप नीचे दिए गए लिंक से बिल का विवरण देख सकते हैं: <a href="{bill_admin_link}">{bill_number}</a>।<br/><br/>सादर,<br/>{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} आवर्ती बिल बनाया गया',
        'body'          => 'नमस्ते,<br/><br/>{vendor_name} के आवर्ती सर्कल के आधार पर, <strong>{bill_number}</strong> चालान स्वचालित रूप से बनाया गया है।<br/><br/>आप नीचे दिए गए लिंक से बिल का विवरण देख सकते हैं: <a href="{bill_admin_link}">{bill_number}</a>।<br/><br/>सादर,<br/>{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => '{revenue_date} भुगतान बनाया गया',
        'body'          => 'प्रिय {customer_name},<br /><br />हमने निम्नलिखित भुगतान तैयार किया है।<br /><br />आप निम्न लिंक से भुगतान विवरण देख सकते हैं : <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />किसी भी प्रश्न के लिए हमसे बेझिझक संपर्क करें..<br /><br />सादर,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => '{revenue_date} भुगतान बनाया गया',
        'body'          => 'प्रिय {{vendor_name}},<br /><br />हमने निम्नलिखित भुगतान तैयार किया है।<br /><br />आप निम्न लिंक से भुगतान विवरण देख सकते हैं : <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />किसी भी प्रश्न के लिए हमसे बेझिझक संपर्क करें..<br /><br />सादर,<br />{company_name}',
    ],
];
