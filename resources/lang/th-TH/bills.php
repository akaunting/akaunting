<?php

return [

    'bill_number'           => 'หมายเลขบิล',
    'bill_date'             => 'วันที่บิล',
    'total_price'           => 'ราคารวม',
    'due_date'              => 'วันครบกำหนด',
    'order_number'          => 'หมายเลขสั่งซื้อ',
    'bill_from'             => 'บิลจาก',

    'quantity'              => 'จำนวน',
    'price'                 => 'ราคา',
    'sub_total'             => 'ยอดรวม',
    'discount'              => 'ส่วนลด',
    'tax_total'             => 'ยอดรวมภาษี',
    'total'                 => 'รวมทั้งหมด',

    'item_name'             => 'ชื่อสินค้า | ชื่อสินค้า',

    'show_discount'         => ':discount% ส่วนลด',
    'add_discount'          => 'เพิ่มส่วนลด',
    'discount_desc'         => 'ของยอดรวม',

    'payment_due'           => 'ครบกำหนดชำระ',
    'amount_due'            => 'ยอดเงินที่ต้องชำระ',
    'paid'                  => 'จ่ายไป',
    'histories'             => 'ประวัติ',
    'payments'              => 'การชำระเงิน',
    'add_payment'           => 'เพิ่มการชำระเงิน',
    'mark_received'         => 'ทำเครื่องหมายได้รับแล้ว',
    'download_pdf'          => 'ดาวน์โหลด PDF',
    'send_mail'             => 'ส่งอีเมล',
    'create_bill'           => 'สร้างบิล',
    'receive_bill'          => 'รับบิล',
    'make_payment'          => 'ทำการชำระเงิน',

    'status' => [
        'draft'             => 'ฉบับร่าง',
        'received'          => 'ได้รับแล้ว',
        'partial'           => 'บางส่วน',
        'paid'              => 'จ่ายแล้ว',
    ],

    'messages' => [
        'received'          => 'บิลทำเครื่องหมายได้รับเรียบร้อยแล้ว!',
        'draft'             => 'นี้เป็น <b>ร่าง</b>บิล และจะมีผลกับแผนภูมิหลังจากได้รับการชำระ',

        'status' => [
            'created'       => 'สร้างขึ้น: วันที่',
            'receive' => [
                'draft'     => 'ยังไม่ได้ส่ง',
                'received'  => 'รับ: วันที่',
            ],
            'paid' => [
                'await'     => 'รอการชำระเงิน',
            ],
        ],
    ],

];
