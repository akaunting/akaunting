<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'चलानी {invoice_number} सिर्जना गरियो |',
        'body'          => 'प्रिय {customer_name},<br /><br />हामीले तपाईको निम्ति देहाय बमोजिमको चलानी निर्माण गरका छौँ: <strong>{invoice_number}</strong>.<br /><br />तपाइँले चलानीको विवरण हेर्न र तलको लिंकबाट भुक्तानीको लागि अघि बढ्न सक्नुहुन्छ: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> कुनै प्रश्न भए हामीलाई सम्पर्क गर्न स्वतन्त्र महसुस गर्नुहोस् |<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} चलानी बाँकी रहेको सूचना',
        'body'          => 'प्रिय {customer_name},<br /><br /> यो <strong>{invoice_number}</strong>  चलानीको लागि बक्यौता रहेको सूचना हो |<br /><br />{invoice_due_date} मिति देखि चलानीको जम्मा  <strong>{invoice_total}</strong> छ | <br /><br />तपाइँले चलानीको विवरण हेर्न र तलको लिंकबाट भुक्तानीको लागि अघि बढ्न सक्नुहुन्छ: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} चलानी बाँकी रहेको सूचना',
        'body'          => 'नमस्कार,<br /><br />{customer_name} ले चलानी नं  <strong>{invoice_number}</strong>को बक्यौता सूचना प्राप्त गर्नुभएको छ |<br /><br />मिति <strong>{invoice_due_date}</strong> बाट  चलानिको रू  {invoice_total} बक्यौता रहेको छ | <br /><br />Yतपाइले तलको लिंकबाट पूर्ण विवरण हेर्न सक्नुहुनेछ: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'आवर्ती चलानी {invoice_number} सिर्जना गरियो |',
        'body'          => 'प्रिय {customer_name}, <br /><br />तपाईको आवर्ती वृत्तको आधारमा हामीले निम्न बमोजिमको चलानी सिर्जना गरेका छौँ: <strong>{invoice_number}</strong>.<br /><br />तपाइले चलानिको विस्तृत विवरण हेर्न र भुक्तानीको लागि अगाडि बढ्न यो लिंकमा प्रयोग गर्न सक्नुहुन्छ: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> कुनै प्रश्न भए हामीलाई सोध्न स्वतन्त्र अनुभव गर्नुहोस<br /><br />शुभेच्छा सहित<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'आवर्ती चलानी {invoice_number} सिर्जना गरियो |',
        'body'          => 'नमस्कार,<br /><br />{customer_name} को आवर्ती वृत्त  <strong>{invoice_number}</strong>को आधारमा स्वत चलानी सिर्जना गरिएको  छ |<br /><br />तपाइले तलको लिंकबाट पूर्ण विवरण हेर्न सक्नुहुनेछ: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Payment received for {invoice_number} invoice',
        'body'          => 'प्रिय {customer_name},<br /><br />भुक्तानीको लागि धन्यवाद | भुक्तानीको विवरण तल दिईएको छ:<br /><br />-------------------------------------------------<br />रकम: <strong>{transaction_total}</strong><br />मिति: <strong>{transaction_paid_date}</strong><br />चलानी सङ्ख्या: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />तपाइले चलानिको विवरण विस्तृत सधैँ यो लिंक प्रयोग गरी हेर्न सक्नुहुन्छ: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> कुनै प्रश्न भए हामीलाई सम्पर्क गर्न तपाईं स्वतन्त्र हुनुहुन्छ |<br /><br />शुभेक्षा सहित,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'चलानी नं {invoice_number} को भुक्तानी प्राप्त भयो |',
        'body'          => 'नमस्कार,<br /><br />{customer_name} ले चलानी नं  <strong>{invoice_number}</strong> को भुक्तानीको अभिलेखन गर्नुभयो|<br /><br /> तपाईंले तलको लिंकबाट चलानीको विस्तृत विवरण हेर्न सक्नुहुन्छ: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'बिल नं {bill_number} पुन: स्मरण सूचना',
        'body'          => 'नमस्कार,<br /><br />यो {vendor_name} बाट प्राप्त बिल नं <strong>{bill_number}</strong> को पुन: स्मरण सूचना हो |<br /><br /> जम्मा विल रकम {bill_total} छ र यो मिति <strong>{bill_due_date} </strong> देखि बक्यौता.<br /><br />तपाईंले तलको लिंकबाट विस्तृत विवरण हेर्न सक्नुहुन्छ: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} आवर्ती विल सिर्जना गरियो |',
        'body'          => 'नमस्कार,<br /><br />{vendor_name} को आवर्ती वृत्त  <strong>{bill_number}</strong>को आधारमा स्वत विल सिर्जना गरिएको  छ |<br /><br />तपाइले तलको लिंकबाट पूर्ण विवरण हेर्न सक्नुहुनेछ: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />शुभेच्छा सहित,<br />{company_name}',
    ],

];
