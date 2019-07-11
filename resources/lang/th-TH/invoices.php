<?php

return [

    'invoice_number'        => 'หมายเลขใบแจ้งหนี้',
    'invoice_date'          => 'วันที่แจ้งหนี้',
    'total_price'           => 'ราคารวม',
    'due_date'              => 'วันครบกำหนด',
    'order_number'          => 'หมายเลขสั่งซื้อ',
    'bill_to'               => 'บิลถึง',

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
    'paid'                  => 'ชำระแล้ว',
    'histories'             => 'ประวัติ',
    'payments'              => 'การชำระเงิน',
    'add_payment'           => 'เพิ่มการชำระเงิน',
    'mark_paid'             => 'ทำเครื่องหมายชำระเงินแล้ว',
    'mark_sent'             => 'ทำเครื่องหมายว่าส่งแล้ว',
    'download_pdf'          => 'ดาวน์โหลด PDF',
    'send_mail'             => 'ส่งอีเมล',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',

    'status' => [
        'draft'             => 'ฉบับร่าง',
        'sent'              => 'ส่ง',
        'viewed'            => 'ดูแล้ว',
        'approved'          => 'อนุมัติ',
        'partial'           => 'บางส่วน',
        'paid'              => 'ชำระแล้ว',
    ],

    'messages' => [
        'email_sent'        => 'อีเมลใบแจ้งหนี้ถูกส่งเรียบร้อยแล้ว!',
        'marked_sent'       => 'ใบแจ้งหนี้ที่ทำเครื่องหมายว่าส่งสำเร็จแล้ว!',
        'email_required'    => 'ไม่มีที่อยู่อีเมลสำหรับลูกค้านี้',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Created on :date',
            'send' => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'คุณได้รับอีเมลฉบับนี้ เพราะคุณมีกำลังจะเกิดขึ้น :amount ใบแจ้งหนี้ ถึง :customer ลูกค้า',
        'button'            => 'ชำระเงินทันที',
    ],

];
