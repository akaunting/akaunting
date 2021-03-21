%skip   T_SPACE       		\s

%token  T_ASSIGNMENT       	:|=
%token  T_COMPARATOR       	>=?|<=?
%token  T_AND		   		(and|AND)(?![^\(\)\s])
%token  T_OR	       		(or|OR)(?![^\(\)\s])
%token  T_NOT	       		(not|NOT)(?![^\(\)\s])
%token  T_IN	       		(in|IN)(?![^\(\)\s])
%token  T_NULL		    	(NULL)(?![^\(\)\s])

%token  T_SINGLE_LQUOTE                         '     	-> single_quote_string
%token  T_DOUBLE_LQUOTE   			            "     	-> double_quote_string
%token  single_quote_string:T_STRING  	        [^']+
%token  double_quote_string:T_STRING  	        [^"]+
%token  single_quote_string:T_SINGLE_RQUOTE  	'      	-> default
%token  double_quote_string:T_DOUBLE_RQUOTE  	"      	-> default

%token  T_LPARENTHESIS 	    \(
%token  T_RPARENTHESIS     	\)
%token  T_DOT               \.
%token  T_COMMA             ,

%token  T_INTEGER           (\d+)(?![^\(\)\s])
%token  T_DECIMAL           (\d+\.\d+)(?![^\(\)\s])
%token  T_TERM              [^\s:><="\'\(\)\.,]+

Expr:
    OrNode()

OrNode:
    AndNode() ( ::T_OR:: AndNode() #OrNode )*

AndNode:
    TerminalNode() ( ::T_AND::? TerminalNode() #AndNode )*

TerminalNode:
    NestedExpr() | NotNode() | RelationshipNode() | NestedRelationshipNode() | QueryNode() | ListNode() | SoloNode()

NestedExpr:
    ::T_LPARENTHESIS:: Expr() ::T_RPARENTHESIS::

#NotNode:
    ::T_NOT:: TerminalNode()

#NestedRelationshipNode:
    (<T_TERM>|NestedTerms()) ::T_ASSIGNMENT:: ::T_LPARENTHESIS:: Expr() ::T_RPARENTHESIS:: (Operator() <T_INTEGER>)?

#RelationshipNode:
    NestedTerms() (Operator() NullableScalar())?

#NestedTerms:
    <T_TERM> (::T_DOT:: <T_TERM>)+

#QueryNode:
    <T_TERM> Operator() NullableScalar()

#ListNode:
    <T_TERM> ::T_IN:: ::T_LPARENTHESIS:: ScalarList() ::T_RPARENTHESIS:: |
    <T_TERM> ::T_ASSIGNMENT:: ScalarList()

#SoloNode:
    Scalar()

#ScalarList:
    Scalar() ( ::T_COMMA:: Scalar() )*

Scalar:
    String() | Number() |  <T_TERM>

NullableScalar:
    Scalar() | <T_NULL>

String:
    ::T_SINGLE_LQUOTE:: <T_STRING>? ::T_SINGLE_RQUOTE:: |
    ::T_DOUBLE_LQUOTE:: <T_STRING>? ::T_DOUBLE_RQUOTE::

Number:
    <T_INTEGER> |  <T_DECIMAL>

Operator:
    <T_ASSIGNMENT> | <T_COMPARATOR>
