<?php

return [

    'invoice_number'        => '청구서 번호',
    'invoice_date'          => '청구서 날짜',
    'total_price'           => '총 가격',
    'due_date'              => '마감일',
    'order_number'          => '주문 번호',
    'bill_to'               => '영수증 To',

    'quantity'              => '수량',
    'price'                 => '가격',
    'sub_total'             => '부분합',
    'discount'              => '할인',
    'tax_total'             => '총 세금',
    'total'                 => '총계',

    'item_name'             => '항목 이름|항목 이름',

    'show_discount'         => ':discount% 할인',
    'add_discount'          => '할인 추가',
    'discount_desc'         => '의 부분합',

    'payment_due'           => '예정 상환 금액',
    'paid'                  => '지불됨',
    'histories'             => '이력',
    'payments'              => '결제',
    'add_payment'           => '결제수단 추가',
    'mark_paid'             => '결제됨으로 표시',
    'mark_sent'             => '보냄으로 표시',
    'download_pdf'          => 'PDF 다운로드',
    'send_mail'             => '이메일 보내기',
    'all_invoices'          => '모든 청구서를 보기 위해 로그인',
    'create_invoice'        => '청구서 작성',
    'send_invoice'          => '청구서 전송',
    'get_paid'              => '돈을 받음',

    'status' => [
        'draft'             => '초안',
        'sent'              => '보낸 메시지',
        'viewed'            => '읽음',
        'approved'          => '승인됨',
        'partial'           => '부분',
        'paid'              => '지불됨',
    ],

    'messages' => [
        'email_sent'        => '청구서 메일이 성공적으로 전송되었습니다!',
        'marked_sent'       => '청구서가 성공적으로 읽음으로 표시되었습니다!',
        'email_required'    => '이 고객을 위한 이메일 주소가 없습니다!',
        'draft'             => '이 청구서는 <b>초안</b>으로, 발신 표시가 된 이후에 차트에 반영됩니다.',

        'status' => [
            'created'       => '생성 날짜 :date',
            'send' => [
                'draft'     => '전송 실패',
                'sent'      => '보낸날짜 :date',
            ],
            'paid' => [
                'await'     => '대기중인 결제',
            ],
        ],
    ],

    'notification' => [
        'message'           => ':customer 고객을 위한 :amount 개의 청구서가 다가오고 있어, 이메일을 보냅니다.',
        'button'            => '지금 구매',
    ],

];
