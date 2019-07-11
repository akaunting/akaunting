<?php

return [

    'success' => [
        'added'             => ':type 추가됨!',
        'updated'           => ':type 갱신됨!',
        'deleted'           => ':type 삭제됨!',
        'duplicated'        => ':type 복제됨!',
        'imported'          => ':type 가져옴!',
        'enabled'           => ':type 활성화됨!',
        'disabled'          => ':type 비활성화됨!',
    ],

    'error' => [
        'over_payment'      => '오류: 지불이 추가되지 않았습니다! 당신이 입력한 금액 :amount 는 총 합에 반영되지 않습니다.',
        'not_user_company'  => '오류: 이 기업을 관리할 수 없습니다!',
        'customer'          => '오류: 사용자가 생성되지 않았습니다! :name 이 이미 이 이메일 주소를 사용하고 있습니다.',
        'no_file'           => '오류: 선택된 파일이 없습니다!',
        'last_category'     => '오류: 최근 :type 카테고리를 삭제할 수 없습니다!',
        'invalid_token'     => '오류: 입력된 토큰은 유효하지 않습니다!',
        'import_column'     => '오류: :message 시트 이름: :sheet. 줄 번호: :line.',
        'import_sheet'      => '오류: 시트명이 유효하지 않습니다. 샘플 파일을 확인하세요.',
    ],

    'warning' => [
        'deleted'           => '경고: :<b>:name</b>는 :text 와 연관되어 있어, 삭제할 수 없습니다.',
        'disabled'          => '경고: <b>:name</b>는 :text 와 연관되어 있어, 비활성화할 수 없습니다.',
        'disable_code'      => '경고: <b>:name</b> 통화는 :text 와 연관되어 있어, 비활성화하거나 수정할 수 없습니다.',
    ],

];
