<?php

namespace Lorisleiva\LaravelSearchString\Compiler;

class CompiledParser extends \Hoa\Compiler\Llk\Parser
{
    public function __construct()
    {
        parent::__construct(
            [
                'default' => [
                    'skip' => '\s',
                    'T_ASSIGNMENT' => ':|=',
                    'T_COMPARATOR' => '>=?|<=?',
                    'T_AND' => '(and|AND)(?![^\(\)\s])',
                    'T_OR' => '(or|OR)(?![^\(\)\s])',
                    'T_NOT' => '(not|NOT)(?![^\(\)\s])',
                    'T_IN' => '(in|IN)(?![^\(\)\s])',
                    'T_NULL' => '(NULL)(?![^\(\)\s])',
                    'T_SINGLE_LQUOTE:single_quote_string' => '\'',
                    'T_DOUBLE_LQUOTE:double_quote_string' => '"',
                    'T_LPARENTHESIS' => '\(',
                    'T_RPARENTHESIS' => '\)',
                    'T_DOT' => '\.',
                    'T_COMMA' => ',',
                    'T_INTEGER' => '(\d+)(?![^\(\)\s])',
                    'T_DECIMAL' => '(\d+\.\d+)(?![^\(\)\s])',
                    'T_TERM' => '[^\s:><="\\\'\(\)\.,]+',
                ],
                'single_quote_string' => [
                    'T_STRING' => '[^\']+',
                    'T_SINGLE_RQUOTE:default' => '\'',
                ],
                'double_quote_string' => [
                    'T_STRING' => '[^"]+',
                    'T_DOUBLE_RQUOTE:default' => '"',
                ],
            ],
            [
                'Expr' => new \Hoa\Compiler\Llk\Rule\Concatenation('Expr', ['OrNode'], null),
                1 => new \Hoa\Compiler\Llk\Rule\Token(1, 'T_OR', null, -1, false),
                2 => new \Hoa\Compiler\Llk\Rule\Concatenation(2, [1, 'AndNode'], '#OrNode'),
                3 => new \Hoa\Compiler\Llk\Rule\Repetition(3, 0, -1, 2, null),
                'OrNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('OrNode', ['AndNode', 3], null),
                5 => new \Hoa\Compiler\Llk\Rule\Token(5, 'T_AND', null, -1, false),
                6 => new \Hoa\Compiler\Llk\Rule\Repetition(6, 0, 1, 5, null),
                7 => new \Hoa\Compiler\Llk\Rule\Concatenation(7, [6, 'TerminalNode'], '#AndNode'),
                8 => new \Hoa\Compiler\Llk\Rule\Repetition(8, 0, -1, 7, null),
                'AndNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('AndNode', ['TerminalNode', 8], null),
                'TerminalNode' => new \Hoa\Compiler\Llk\Rule\Choice('TerminalNode', ['NestedExpr', 'NotNode', 'RelationshipNode', 'NestedRelationshipNode', 'QueryNode', 'ListNode', 'SoloNode'], null),
                11 => new \Hoa\Compiler\Llk\Rule\Token(11, 'T_LPARENTHESIS', null, -1, false),
                12 => new \Hoa\Compiler\Llk\Rule\Token(12, 'T_RPARENTHESIS', null, -1, false),
                'NestedExpr' => new \Hoa\Compiler\Llk\Rule\Concatenation('NestedExpr', [11, 'Expr', 12], null),
                14 => new \Hoa\Compiler\Llk\Rule\Token(14, 'T_NOT', null, -1, false),
                'NotNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('NotNode', [14, 'TerminalNode'], '#NotNode'),
                16 => new \Hoa\Compiler\Llk\Rule\Token(16, 'T_TERM', null, -1, true),
                17 => new \Hoa\Compiler\Llk\Rule\Concatenation(17, [16], '#NestedRelationshipNode'),
                18 => new \Hoa\Compiler\Llk\Rule\Concatenation(18, ['NestedTerms'], '#NestedRelationshipNode'),
                19 => new \Hoa\Compiler\Llk\Rule\Choice(19, [17, 18], null),
                20 => new \Hoa\Compiler\Llk\Rule\Token(20, 'T_ASSIGNMENT', null, -1, false),
                21 => new \Hoa\Compiler\Llk\Rule\Token(21, 'T_LPARENTHESIS', null, -1, false),
                22 => new \Hoa\Compiler\Llk\Rule\Token(22, 'T_RPARENTHESIS', null, -1, false),
                23 => new \Hoa\Compiler\Llk\Rule\Token(23, 'T_INTEGER', null, -1, true),
                24 => new \Hoa\Compiler\Llk\Rule\Concatenation(24, ['Operator', 23], null),
                25 => new \Hoa\Compiler\Llk\Rule\Repetition(25, 0, 1, 24, null),
                'NestedRelationshipNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('NestedRelationshipNode', [19, 20, 21, 'Expr', 22, 25], null),
                27 => new \Hoa\Compiler\Llk\Rule\Concatenation(27, ['Operator', 'NullableScalar'], '#RelationshipNode'),
                28 => new \Hoa\Compiler\Llk\Rule\Repetition(28, 0, 1, 27, null),
                'RelationshipNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('RelationshipNode', ['NestedTerms', 28], null),
                30 => new \Hoa\Compiler\Llk\Rule\Token(30, 'T_TERM', null, -1, true),
                31 => new \Hoa\Compiler\Llk\Rule\Token(31, 'T_DOT', null, -1, false),
                32 => new \Hoa\Compiler\Llk\Rule\Token(32, 'T_TERM', null, -1, true),
                33 => new \Hoa\Compiler\Llk\Rule\Concatenation(33, [31, 32], '#NestedTerms'),
                34 => new \Hoa\Compiler\Llk\Rule\Repetition(34, 1, -1, 33, null),
                'NestedTerms' => new \Hoa\Compiler\Llk\Rule\Concatenation('NestedTerms', [30, 34], null),
                36 => new \Hoa\Compiler\Llk\Rule\Token(36, 'T_TERM', null, -1, true),
                'QueryNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('QueryNode', [36, 'Operator', 'NullableScalar'], '#QueryNode'),
                38 => new \Hoa\Compiler\Llk\Rule\Token(38, 'T_TERM', null, -1, true),
                39 => new \Hoa\Compiler\Llk\Rule\Token(39, 'T_IN', null, -1, false),
                40 => new \Hoa\Compiler\Llk\Rule\Token(40, 'T_LPARENTHESIS', null, -1, false),
                41 => new \Hoa\Compiler\Llk\Rule\Token(41, 'T_RPARENTHESIS', null, -1, false),
                42 => new \Hoa\Compiler\Llk\Rule\Concatenation(42, [38, 39, 40, 'ScalarList', 41], '#ListNode'),
                43 => new \Hoa\Compiler\Llk\Rule\Token(43, 'T_TERM', null, -1, true),
                44 => new \Hoa\Compiler\Llk\Rule\Token(44, 'T_ASSIGNMENT', null, -1, false),
                45 => new \Hoa\Compiler\Llk\Rule\Concatenation(45, [43, 44, 'ScalarList'], '#ListNode'),
                'ListNode' => new \Hoa\Compiler\Llk\Rule\Choice('ListNode', [42, 45], null),
                47 => new \Hoa\Compiler\Llk\Rule\Concatenation(47, ['Scalar'], null),
                'SoloNode' => new \Hoa\Compiler\Llk\Rule\Concatenation('SoloNode', [47], '#SoloNode'),
                49 => new \Hoa\Compiler\Llk\Rule\Token(49, 'T_COMMA', null, -1, false),
                50 => new \Hoa\Compiler\Llk\Rule\Concatenation(50, [49, 'Scalar'], '#ScalarList'),
                51 => new \Hoa\Compiler\Llk\Rule\Repetition(51, 0, -1, 50, null),
                'ScalarList' => new \Hoa\Compiler\Llk\Rule\Concatenation('ScalarList', ['Scalar', 51], null),
                53 => new \Hoa\Compiler\Llk\Rule\Token(53, 'T_TERM', null, -1, true),
                'Scalar' => new \Hoa\Compiler\Llk\Rule\Choice('Scalar', ['String', 'Number', 53], null),
                55 => new \Hoa\Compiler\Llk\Rule\Token(55, 'T_NULL', null, -1, true),
                'NullableScalar' => new \Hoa\Compiler\Llk\Rule\Choice('NullableScalar', ['Scalar', 55], null),
                57 => new \Hoa\Compiler\Llk\Rule\Token(57, 'T_SINGLE_LQUOTE', null, -1, false),
                58 => new \Hoa\Compiler\Llk\Rule\Token(58, 'T_STRING', null, -1, true),
                59 => new \Hoa\Compiler\Llk\Rule\Repetition(59, 0, 1, 58, null),
                60 => new \Hoa\Compiler\Llk\Rule\Token(60, 'T_SINGLE_RQUOTE', null, -1, false),
                61 => new \Hoa\Compiler\Llk\Rule\Concatenation(61, [57, 59, 60], null),
                62 => new \Hoa\Compiler\Llk\Rule\Token(62, 'T_DOUBLE_LQUOTE', null, -1, false),
                63 => new \Hoa\Compiler\Llk\Rule\Token(63, 'T_STRING', null, -1, true),
                64 => new \Hoa\Compiler\Llk\Rule\Repetition(64, 0, 1, 63, null),
                65 => new \Hoa\Compiler\Llk\Rule\Token(65, 'T_DOUBLE_RQUOTE', null, -1, false),
                66 => new \Hoa\Compiler\Llk\Rule\Concatenation(66, [62, 64, 65], null),
                'String' => new \Hoa\Compiler\Llk\Rule\Choice('String', [61, 66], null),
                68 => new \Hoa\Compiler\Llk\Rule\Token(68, 'T_INTEGER', null, -1, true),
                69 => new \Hoa\Compiler\Llk\Rule\Token(69, 'T_DECIMAL', null, -1, true),
                'Number' => new \Hoa\Compiler\Llk\Rule\Choice('Number', [68, 69], null),
                71 => new \Hoa\Compiler\Llk\Rule\Token(71, 'T_ASSIGNMENT', null, -1, true),
                72 => new \Hoa\Compiler\Llk\Rule\Token(72, 'T_COMPARATOR', null, -1, true),
                'Operator' => new \Hoa\Compiler\Llk\Rule\Choice('Operator', [71, 72], null),
            ],
            [
            ]
        );

        $this->getRule('Expr')->setPPRepresentation(' OrNode()');
        $this->getRule('OrNode')->setPPRepresentation(' AndNode() ( ::T_OR:: AndNode() #OrNode )*');
        $this->getRule('AndNode')->setPPRepresentation(' TerminalNode() ( ::T_AND::? TerminalNode() #AndNode )*');
        $this->getRule('TerminalNode')->setPPRepresentation(' NestedExpr() | NotNode() | RelationshipNode() | NestedRelationshipNode() | QueryNode() | ListNode() | SoloNode()');
        $this->getRule('NestedExpr')->setPPRepresentation(' ::T_LPARENTHESIS:: Expr() ::T_RPARENTHESIS::');
        $this->getRule('NotNode')->setDefaultId('#NotNode');
        $this->getRule('NotNode')->setPPRepresentation(' ::T_NOT:: TerminalNode()');
        $this->getRule('NestedRelationshipNode')->setDefaultId('#NestedRelationshipNode');
        $this->getRule('NestedRelationshipNode')->setPPRepresentation(' (<T_TERM>|NestedTerms()) ::T_ASSIGNMENT:: ::T_LPARENTHESIS:: Expr() ::T_RPARENTHESIS:: (Operator() <T_INTEGER>)?');
        $this->getRule('RelationshipNode')->setDefaultId('#RelationshipNode');
        $this->getRule('RelationshipNode')->setPPRepresentation(' NestedTerms() (Operator() NullableScalar())?');
        $this->getRule('NestedTerms')->setDefaultId('#NestedTerms');
        $this->getRule('NestedTerms')->setPPRepresentation(' <T_TERM> (::T_DOT:: <T_TERM>)+');
        $this->getRule('QueryNode')->setDefaultId('#QueryNode');
        $this->getRule('QueryNode')->setPPRepresentation(' <T_TERM> Operator() NullableScalar()');
        $this->getRule('ListNode')->setDefaultId('#ListNode');
        $this->getRule('ListNode')->setPPRepresentation(' <T_TERM> ::T_IN:: ::T_LPARENTHESIS:: ScalarList() ::T_RPARENTHESIS:: | <T_TERM> ::T_ASSIGNMENT:: ScalarList()');
        $this->getRule('SoloNode')->setDefaultId('#SoloNode');
        $this->getRule('SoloNode')->setPPRepresentation(' Scalar()');
        $this->getRule('ScalarList')->setDefaultId('#ScalarList');
        $this->getRule('ScalarList')->setPPRepresentation(' Scalar() ( ::T_COMMA:: Scalar() )*');
        $this->getRule('Scalar')->setPPRepresentation(' String() | Number() |  <T_TERM>');
        $this->getRule('NullableScalar')->setPPRepresentation(' Scalar() | <T_NULL>');
        $this->getRule('String')->setPPRepresentation(' ::T_SINGLE_LQUOTE:: <T_STRING>? ::T_SINGLE_RQUOTE:: | ::T_DOUBLE_LQUOTE:: <T_STRING>? ::T_DOUBLE_RQUOTE::');
        $this->getRule('Number')->setPPRepresentation(' <T_INTEGER> |  <T_DECIMAL>');
        $this->getRule('Operator')->setPPRepresentation(' <T_ASSIGNMENT> | <T_COMPARATOR>');
    }
}
