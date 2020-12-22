<?php

return [

    'bill_number'           => '계산서 번호',
    'bill_date'             => '계산서 날짜',
    'total_price'           => '총 가격',
    'due_date'              => '마감일',
    'order_number'          => '주문 번호',
    'bill_from'             => '계산서 출처',

    'quantity'              => '수량',
    'price'                 => '가격',
    'sub_total'             => '부분합',
    'discount'              => '할인',
    'tax_total'             => '총 세금',
    'total'                 => '총계',

    'item_name'             => '항목 이름|항목 이름',

    'show_discount'         => ':discount% 할인',
    'add_discount'          => '추가 할인',
    'discount_desc'         => '부분합의',

    'payment_due'           => '예정 상환 금액',
    'amount_due'            => '미납 금액',
    'paid'                  => '지불됨',
    'histories'             => '이력',
    'payments'              => '결제',
    'add_payment'           => '결제수단 추가',
    'mark_received'         => '읽음으로 표시',
    'download_pdf'          => 'PDF 다운로드',
    'send_mail'             => '이메일 보내기',
    'create_bill'           => '계산서 발행',
    'receive_bill'          => '계산서를 받음',
    'make_payment'          => '지불',

    'statuses' => [
        'draft'             => '초안',
        'received'          => '수신됨',
        'partial'           => '부분',
        'paid'              => '지불됨',
    ],

    'messages' => [
        'received'          => '계산서를 성공적으로 수신 표시했음',
        'draft'             => '이 계산서는 <b>초안</b>으로, 수신 표시가 된 이후에 차트에 반영됩니다.',

        'status' => [
            'created'       => '생성 날짜 :date',
            'receive' => [
                'draft'     => '전송 실패',
                'received'  => '수신 날짜 :date',
            ],
            'paid' => [
                'await'     => '대기중인 결제',
            ],
        ],
    ],

];
