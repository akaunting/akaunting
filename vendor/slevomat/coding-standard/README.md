# Slevomat Coding Standard

[![Latest version](https://img.shields.io/packagist/v/slevomat/coding-standard.svg?colorB=007EC6)](https://packagist.org/packages/slevomat/coding-standard)
[![Downloads](https://img.shields.io/packagist/dt/slevomat/coding-standard.svg?colorB=007EC6)](https://packagist.org/packages/slevomat/coding-standard)
[![Build status](https://github.com/slevomat/coding-standard/workflows/Build/badge.svg?branch=master)](https://github.com/slevomat/coding-standard/actions?query=workflow%3ABuild+branch%3Amaster)
[![Code coverage](https://codecov.io/gh/slevomat/coding-standard/branch/master/graph/badge.svg)](https://codecov.io/gh/slevomat/coding-standard)
![PHPStan](https://img.shields.io/badge/style-level%207-brightgreen.svg?&label=phpstan)

Slevomat Coding Standard for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) provides sniffs that fall into three categories:

* Functional - improving the safety and behaviour of code
* Cleaning - detecting dead code
* Formatting - rules for consistent code looks

## Table of contents

1. [Sniffs included in this standard](#sniffs-included-in-this-standard)
  - [Functional - improving the safety and behaviour of code](#functional---improving-the-safety-and-behaviour-of-code)
  - [Cleaning - detecting dead code](#cleaning---detecting-dead-code)
  - [Formatting - rules for consistent code looks](#formatting---rules-for-consistent-code-looks)
2. [Installation](#installation)
3. [How to run the sniffs](#how-to-run-the-sniffs)
  - [Choose which sniffs to run](#choose-which-sniffs-to-run)
  - [Using all sniffs from the standard](#using-all-sniffs-from-the-standard)
4. [Fixing errors automatically](#fixing-errors-automatically)
5. [Suppressing sniffs locally](#suppressing-sniffs-locally)
6. [Contributing](#contributing)

## Sniffs included in this standard

🔧 = [Automatic errors fixing](#fixing-errors-automatically)

🚧 = [Sniff check can be suppressed locally](#suppressing-sniffs-locally)

### Functional - improving the safety and behaviour of code

#### SlevomatCodingStandard.TypeHints.ParameterTypeHint 🔧🚧

* Checks for missing parameter typehints in case they can be declared natively. If the phpDoc contains something that can be written as a native PHP 7.0+ typehint, this sniff reports that.
* Checks for useless `@param` annotations. If the native method declaration contains everything and the phpDoc does not add anything useful, it's reported as useless and can optionally be automatically removed with `phpcbf`.
* Forces to specify what's in traversable types like `array`, `iterable` and `\Traversable`.

Sniff provides the following settings:

* `enableObjectTypeHint`: enforces to transform `@param object` into native `object` typehint. It's on by default if you're on PHP 7.2+
* `traversableTypeHints`: enforces which typehints must have specified contained type. E. g. if you set this to `\Doctrine\Common\Collections\Collection`, then `\Doctrine\Common\Collections\Collection` must always be supplied with the contained type: `\Doctrine\Common\Collections\Collection|Foo[]`.

This sniff can cause an error if you're overriding or implementing a parent method which does not have typehints. In such cases add `@phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint` annotation to the method to have this sniff skip it.

#### SlevomatCodingStandard.TypeHints.PropertyTypeHint 🔧🚧

* Checks for missing property typehints in case they can be declared natively. If the phpDoc contains something that can be written as a native PHP 7.4+ typehint, this sniff reports that.
* Checks for useless `@var` annotations. If the native method declaration contains everything and the phpDoc does not add anything useful, it's reported as useless and can optionally be automatically removed with `phpcbf`.
* Forces to specify what's in traversable types like `array`, `iterable` and `\Traversable`.

Sniff provides the following settings:

* `enableNativeTypeHint`: enforces to transform `@var int` into native `int` typehint. It's on by default if you're on PHP 7.4+
* `traversableTypeHints`: enforces which typehints must have specified contained type. E. g. if you set this to `\Doctrine\Common\Collections\Collection`, then `\Doctrine\Common\Collections\Collection` must always be supplied with the contained type: `\Doctrine\Common\Collections\Collection|Foo[]`.

This sniff can cause an error if you're overriding parent property which does not have typehints. In such cases add `@phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint` annotation to the property to have this sniff skip it.

#### SlevomatCodingStandard.TypeHints.ReturnTypeHint 🔧🚧

* Checks for missing return typehints in case they can be declared natively. If the phpDoc contains something that can be written as a native PHP 7.0+ typehint, this sniff reports that.
* Checks for useless `@return` annotations. If the native method declaration contains everything and the phpDoc does not add anything useful, it's reported as useless and can optionally be automatically removed with `phpcbf`.
* Forces to specify what's in traversable types like `array`, `iterable` and `\Traversable`.

Sniff provides the following settings:

* `enableObjectTypeHint`: enforces to transform `@param object` into native `object` typehint. It's on by default if you're on PHP 7.2+
* `traversableTypeHints`: enforces which typehints must have specified contained type. E. g. if you set this to `\Doctrine\Common\Collections\Collection`, then `\Doctrine\Common\Collections\Collection` must always be supplied with the contained type: `\Doctrine\Common\Collections\Collection|Foo[]`.

This sniff can cause an error if you're overriding or implementing a parent method which does not have typehints. In such cases add `@phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint` annotation to the method to have this sniff skip it.

#### SlevomatCodingStandard.TypeHints.UselessConstantTypeHint 🔧

Reports useless `@var` annotation (or whole documentation comment) for constants because the type of constant is always clear.

#### SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly 🔧🚧

In PHP 7.0, a [`Throwable` interface was added](https://wiki.php.net/rfc/throwable-interface) that allows catching and handling errors in more cases than `Exception` previously allowed. So, if the catch statement contained `Exception` on PHP 5.x, it means it should probably be rewritten to reference `Throwable` on PHP 7.x. This sniff enforces that.

#### SlevomatCodingStandard.TypeHints.DeclareStrictTypes 🔧

Enforces having `declare(strict_types = 1)` at the top of each PHP file. Allows configuring how many newlines should be between the `<?php` opening tag and the `declare` statement.

Sniff provides the following settings:

* `newlinesCountBetweenOpenTagAndDeclare`: allows to set 0 to N newlines to be between `<?php` and `declare`
* `newlinesCountAfterDeclare`: allows to set 0 to N newlines to be between `declare` and next statement
* `spacesCountAroundEqualsSign`: allows to set number of required spaces around the `=` operator

#### SlevomatCodingStandard.Arrays.DisallowImplicitArrayCreation

Disallows implicit array creation.

#### SlevomatCodingStandard.Classes.ClassStructure 🔧

Checks that class/trait/interface members are in the correct order.

Sniff provides the following settings:

* `groups`: order of groups. Use multiple groups in one `<element value="">` to not differentiate among them. You can use specific groups or shortcuts.
* `enableFinalMethods`: enables groups for `final` methods

**List of supported groups**:
uses,
public constants, protected constants, private constants,
public properties, public static properties, protected properties, protected static properties, private properties, private static properties,
constructor, static constructors, destructor, magic methods,
public methods, protected methods, private methods,
public final methods, public static final methods, protected final methods, protected static final methods,
public abstract methods, public static abstract methods, protected abstract methods, protected static abstract methods,
public static methods, protected static methods, private static methods,
private methods

**List of supported shortcuts**:
constants, properties, static properties, methods, all public methods, all protected methods, all private methods, static methods, final methods, abstract methods

```xml
<rule ref="SlevomatCodingStandard.Classes.ClassStructure">
	<properties>
		<property name="groups" type="array">
			<element value="uses"/>

			<!-- Public constants are first but you don't care about the order of protected or private constants -->
			<element value="public constants"/>
			<element value="constants"/>

			<!-- You don't care about the order among the properties. The same can be done with "properties" shortcut -->
			<element value="public properties, protected properties, private properties"/>

			<!-- Constructor is first, then all public methods, then protected/private methods and magic methods are last -->
			<element value="constructor"/>
			<element value="all public methods"/>
			<element value="methods"/>
			<element value="magic methods"/>
		</property>
	</properties>
</rule>
```

#### SlevomatCodingStandard.Classes.DisallowLateStaticBindingForConstants 🔧

Disallows late static binding for constants.

#### SlevomatCodingStandard.Classes.UselessLateStaticBinding 🔧

Reports useless late static binding.

#### SlevomatCodingStandard.ControlStructures.AssignmentInCondition

Disallows assignments in `if`, `elseif` and `do-while` loop conditions:

```php
if ($file = findFile($path)) {

}
```

Assignment in `while` loop condition is specifically allowed because it's commonly used.

This is a great addition to already existing `SlevomatCodingStandard.ControlStructures.DisallowYodaComparison` because it prevents the danger of assigning something by mistake instead of using comparison operator like `===`.

Sniff provides the following settings:
* `ignoreAssignmentsInsideFunctionCalls`: ignores assignment inside function calls, like this:

```php
if (in_array(1, $haystack, $strict = true)) {

}
```

#### SlevomatCodingStandard.ControlStructures.DisallowContinueWithoutIntegerOperandInSwitch 🔧

Disallows use of `continue` without integer operand in `switch` because it emits a warning in PHP 7.3 and higher.

#### SlevomatCodingStandard.ControlStructures.DisallowEmpty

Disallows use of `empty()`.

#### SlevomatCodingStandard.ControlStructures.RequireNullCoalesceOperator 🔧

Requires use of null coalesce operator when possible.

#### SlevomatCodingStandard.ControlStructures.RequireNullCoalesceEqualOperator 🔧

Requires use of null coalesce equal operator when possible.

This sniff provides the following setting:

* `enable`: either to enable or no this sniff. By default, it is enabled for PHP versions 7.4 or higher.

#### SlevomatCodingStandard.ControlStructures.EarlyExit 🔧

Requires use of early exit.

Sniff provides the following settings:

* `ignoreStandaloneIfInScope`: ignores `if` that is standalone in scope, like this:

```php
foreach ($values as $value) {
	if ($value) {
		doSomething();
	}
}
```

* `ignoreOneLineTrailingIf`: ignores `if` that has one line content and is on the last position in scope, like this:

```php
foreach ($values as $value) {
	$value .= 'whatever';

	if ($value) {
		doSomething();
	}
}
```

* `ignoreTrailingIfWithOneInstruction`: ignores `if` that has only one instruction and is on the last position in scope, like this:

```php
foreach ($values as $value) {
	$value .= 'whatever';

	if ($value) {
		doSomething(function () {
			// Anything
		});
	}
}
```

#### SlevomatCodingStandard.Functions.StrictCall

Some functions have `$strict` parameter. This sniff reports calls to these functions without the parameter or with `$strict = false`.

#### SlevomatCodingStandard.Functions.StaticClosure 🔧

Reports closures not using `$this` that are not declared `static`.

#### SlevomatCodingStandard.PHP.DisallowDirectMagicInvokeCall 🔧

Disallows direct call of `__invoke()`.

#### SlevomatCodingStandard.Operators.DisallowEqualOperators 🔧

Disallows using loose `==` and `!=` comparison operators. Use `===` and `!==` instead, they are much more secure and predictable.

#### SlevomatCodingStandard.Operators.DisallowIncrementAndDecrementOperators

Disallows using `++` and `--` operators.

#### SlevomatCodingStandard.Operators.RequireOnlyStandaloneIncrementAndDecrementOperators

Reports `++` and `--` operators not used standalone.

#### SlevomatCodingStandard.Operators.RequireCombinedAssignmentOperator 🔧

Requires using combined assignment operators, eg `+=`, `.=` etc.

### Cleaning - detecting dead code

#### SlevomatCodingStandard.Classes.UnusedPrivateElements 🚧

**DEPRECATED**
See https://phpstan.org/blog/detecting-unused-private-properties-methods-constants

Although PHP_CodeSniffer is not suitable for static analysis because it is limited to analysing one file at a time, it is possible to use it to perform certain checks. `UnusedPrivateElementsSniff` checks for unused methods, unused or write-only properties in a class and unused private constants. Reported unused elements are safe to remove.

This is very useful during refactoring to clean up dead code and injected dependencies.

Sniff provides the following settings:

* `alwaysUsedPropertiesAnnotations`: mark certain properties as always used, for example the ones with `@ORM\Column`
* `alwaysUsedPropertiesSuffixes`: mark properties with name ending with a certain string to be always marked as used
* `alwaysUsedMethodsAnnotations`: mark certain methods as always used, for example the ones with `@Serializer\PostDeserialize`

#### SlevomatCodingStandard.Functions.UnusedInheritedVariablePassedToClosure 🔧

Looks for unused inherited variables passed to closure via `use`.

#### SlevomatCodingStandard.Functions.UnusedParameter 🚧

Looks for unused parameters.

#### SlevomatCodingStandard.Functions.UselessParameterDefaultValue 🚧

Looks for useless parameter default value.

#### SlevomatCodingStandard.Namespaces.UnusedUses 🔧

Looks for unused imports from other namespaces.

Sniff provides the following settings:

* `searchAnnotations` (defaults to `false`): enables searching for class names in annotations.
* `ignoredAnnotationNames`: case sensitive list of annotation names that the sniff should ignore (only the name is ignored, annotation content is still searched). Useful for name collisions like `@testCase` annotation and `TestCase` class.
* `ignoredAnnotations`: case sensitive list of annotation names that the sniff ignore completely (both name and content are ignored). Useful for name collisions like `@group Cache` annotation and `Cache` class.

#### SlevomatCodingStandard.Namespaces.UseFromSameNamespace 🔧

Prohibits uses from the same namespace:

```php
namespace Foo;

use Foo\Bar;
```

#### SlevomatCodingStandard.Namespaces.UselessAlias 🔧

Looks for `use` alias that is same as unqualified name.

#### SlevomatCodingStandard.PHP.DisallowReference

Disallows references.

#### SlevomatCodingStandard.PHP.ForbiddenClasses 🔧

Reports usage of forbidden classes, interfaces, parent classes and traits. And provide the following settings:

* `forbiddenClasses`: forbids creating instances with `new` keyword or accessing with `::` operator
* `forbiddenExtends`: forbids extending with `extends` keyword
* `forbiddenInterfaces`: forbids usage in `implements` section
* `forbiddenTraits`: forbids imports with `use` keyword

Optionally can be passed as an alternative for auto fixes. See `phpcs.xml` file example:

```xml
<rule ref="SlevomatCodingStandard.PHP.ForbiddenClasses">
    <properties>
        <property name="forbiddenClasses" type="array">
            <element key="Validator" value="Illuminate\Support\Facades\Validator"/>
        </property>
        <property name="forbiddenTraits" type="array">
            <element key="\AuthTrait" value="null"/>
        </property>
    </properties>
</rule>
```

#### SlevomatCodingStandard.PHP.RequireExplicitAssertion 🔧

Requires assertion via `assert` instead of inline documentation comments.

#### SlevomatCodingStandard.PHP.RequireNowdoc 🔧

Requires nowdoc syntax instead of heredoc when possible.

#### SlevomatCodingStandard.PHP.UselessParentheses 🔧

Looks for useless parentheses.

Sniff provides the following settings:

* `ignoreComplexTernaryConditions` (defaults to `false`): ignores complex ternary conditions - condition must contain `&&`, `||` etc or end of line.

#### SlevomatCodingStandard.PHP.OptimizedFunctionsWithoutUnpacking

PHP optimizes some internal functions into special opcodes on VM level. Such optimization results in much faster execution compared to calling standard function. This only works when these functions are not invoked with argument unpacking (`...`).

The list of these functions varies across PHP versions, but is the same as functions that must be referenced by their global name (either by `\ ` prefix or using `use function`), not a fallback name inside namespaced code.

#### SlevomatCodingStandard.PHP.UselessSemicolon 🔧

Looks for useless semicolons.

#### SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable

Disallows use of super global variables.

#### SlevomatCodingStandard.Variables.DuplicateAssignmentToVariable

Looks for duplicate assignments to a variable.

#### SlevomatCodingStandard.Variables.UnusedVariable

Looks for unused variables.

Sniff provides the following settings:

* `ignoreUnusedValuesWhenOnlyKeysAreUsedInForeach` (defaults to `false`): ignore unused `$value` in foreach when only `$key` is used

```php
foreach ($values as $key => $value) {
	echo $key;
}
```

#### SlevomatCodingStandard.Variables.UselessVariable 🔧

Looks for useless variables.

#### SlevomatCodingStandard.Exceptions.DeadCatch

This sniff finds unreachable catch blocks:

```php
try {
	doStuff();
} catch (\Throwable $e) {
	log($e);
} catch (\InvalidArgumentException $e) {
	// unreachable!
}
```

### Formatting - rules for consistent code looks

#### SlevomatCodingStandard.Arrays.MultiLineArrayEndBracketPlacement 🔧

Enforces reasonable end bracket placement for multi-line arrays.

#### SlevomatCodingStandard.Arrays.SingleLineArrayWhitespace 🔧

Checks whitespace in single line array declarations (whitespace between brackets, around commas, ...).

Sniff provides the following settings:

* `spacesAroundBrackets`: number of spaces you require to have around array brackets
* `enableEmptyArrayCheck` (defaults to `false`): enables check for empty arrays

#### SlevomatCodingStandard.Arrays.TrailingArrayComma 🔧

Commas after last element in an array make adding a new element easier and result in a cleaner versioning diff.

This sniff enforces trailing commas in multi-line arrays and requires short array syntax `[]`.

Sniff provides the following settings:

* `enableAfterHeredoc`: enables/disables trailing commas after HEREDOC/NOWDOC, default based on PHP version.

#### SlevomatCodingStandard.Classes.ClassMemberSpacing 🔧

Checks lines count between different class members, eg. between last property and first method.

Sniff provides the following settings:

* `linesCountBetweenMembers`: lines count between different class members

#### SlevomatCodingStandard.Classes.ConstantSpacing 🔧

Checks that there is a certain number of blank lines between constants.

Sniff provides the following settings:

* `minLinesCountBeforeWithComment`: minimum number of lines before constant with a doc comment
* `maxLinesCountBeforeWithComment`: maximum number of lines before constant with a doc comment
* `minLinesCountBeforeWithoutComment`: minimum number of lines before constant without a doc comment
* `maxLinesCountBeforeWithoutComment`: maximum number of lines before constant without a doc comment

#### SlevomatCodingStandard.Classes.DisallowMultiConstantDefinition 🔧

Disallows multi constant definition.

#### SlevomatCodingStandard.Classes.DisallowMultiPropertyDefinition 🔧

Disallows multi property definition.

#### SlevomatCodingStandard.Classes.MethodSpacing 🔧

Checks that there is a certain number of blank lines between methods.

Sniff provides the following settings:

* `minLinesCount`: minimum number of blank lines
* `maxLinesCount`: maximum number of blank lines

#### SlevomatCodingStandard.Classes.ModernClassNameReference 🔧

Reports use of `__CLASS__`, `get_parent_class()`, `get_called_class()`, `get_class()` and `get_class($this)`.
Class names should be referenced via `::class` constant when possible.

#### SlevomatCodingStandard.Classes.ParentCallSpacing 🔧

Enforces configurable number of lines around parent method call.

Sniff provides the following settings:

* `linesCountBeforeParentCall`: allows to configure the number of lines before parent call.
* `linesCountBeforeFirstParentCall`: allows to configure the number of lines before first parent call.
* `linesCountAfterParentCall`: allows to configure the number of lines after parent call.
* `linesCountAfterLastParentCall`: allows to configure the number of lines after last parent call.

#### SlevomatCodingStandard.Classes.PropertySpacing 🔧

Checks that there is a certain number of blank lines between properties.

Sniff provides the following settings:

* `minLinesCountBeforeWithComment`: minimum number of lines before property with a doc comment
* `maxLinesCountBeforeWithComment`: maximum number of lines before property with a doc comment
* `minLinesCountBeforeWithoutComment`: minimum number of lines before property without a doc comment
* `maxLinesCountBeforeWithoutComment`: maximum number of lines before property without a doc comment

#### SlevomatCodingStandard.Classes.RequireMultiLineMethodSignature 🔧

Enforces method signature to be splitted to more lines so each parameter is on its own line.

Sniff provides the following settings:

* `minLineLength`: specifies min line length to enforce signature to be splitted. Use 0 value to enforce for all methods, regardless of length.

* `includedMethodPatterns`: allows to configure which methods are included in sniff detection. This is an array of regular expressions (PCRE) with delimiters. You should not use this with `excludedMethodPatterns`, as it will not work properly.

* `excludedMethodPatterns`: allows to configure which methods are excluded from sniff detection. This is an array of regular expressions (PCRE) with delimiters. You should not use this with `includedMethodPatterns`, as it will not work properly.

#### SlevomatCodingStandard.Classes.RequireSingleLineMethodSignature 🔧

Enforces method signature to be on a single line.

Sniff provides the following settings:

* `maxLineLength`: specifies max allowed line length. If signature would fit on it, it's enforced. Use 0 value to enforce for all methods, regardless of length.

* `includedMethodPatterns`: allows to configure which methods are included in sniff detection. This is an array of regular expressions (PCRE) with delimiters. You should not use this with `excludedMethodPatterns`, as it will not work properly.

* `excludedMethodPatterns`: allows to configure which methods are excluded from sniff detection. This is an array of regular expressions (PCRE) with delimiters. You should not use this with `includedMethodPatterns`, as it will not work properly.

#### SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming

Reports use of superfluous prefix or suffix "Abstract" for abstract classes.

#### SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming

Reports use of superfluous prefix or suffix "Interface" for interfaces.

#### SlevomatCodingStandard.Classes.SuperfluousExceptionNaming

Reports use of superfluous suffix "Exception" for exceptions.

#### SlevomatCodingStandard.Classes.SuperfluousErrorNaming

Reports use of superfluous suffix "Error" for errors.

#### SlevomatCodingStandard.Classes.SuperfluousTraitNaming

Reports use of superfluous suffix "Trait" for traits.

#### SlevomatCodingStandard.Classes.TraitUseDeclaration 🔧

Prohibits multiple traits separated by commas in one `use` statement.

#### SlevomatCodingStandard.Classes.TraitUseSpacing 🔧

Enforces configurable number of lines before first `use`, after last `use` and between two `use` statements.

Sniff provides the following settings:

* `linesCountBeforeFirstUse`: allows to configure the number of lines before first `use`.
* `linesCountBeforeFirstUseWhenFirstInClass`: allows to configure the number of lines before first `use` when the `use` is the first statement in the class.
* `linesCountBetweenUses`: allows to configure the number of lines between two `use` statements.
* `linesCountAfterLastUse`: allows to configure the number of lines after last `use`.
* `linesCountAfterLastUseWhenLastInClass`: allows to configure the number of lines after last `use` when the `use` is the last statement in the class.

#### SlevomatCodingStandard.ControlStructures.BlockControlStructureSpacing 🔧

Enforces configurable number of lines around block control structures (if, foreach, ...).

Sniff provides the following settings:

* `linesCountBeforeControlStructure`: allows to configure the number of lines before control structure.
* `linesCountBeforeFirstControlStructure`: allows to configure the number of lines before first control structure.
* `linesCountAfterControlStructure`: allows to configure the number of lines after control structure.
* `linesCountAfterLastControlStructure`: allows to configure the number of lines after last control structure.
* `tokensToCheck`: allows to narrow the list of checked tokens.

For example, with the following setting, only `if` and `switch` tokens are checked.

```xml
<rule ref="SlevomatCodingStandard.ControlStructures.BlockControlStructureSpacing">
	<properties>
		<property name="tokensToCheck" type="array">
			<element value="T_IF"/>
			<element value="T_SWITCH"/>
		</property>
	</properties>
</rule>
```

#### SlevomatCodingStandard.ControlStructures.JumpStatementsSpacing 🔧

Enforces configurable number of lines around jump statements (continue, return, ...).

Sniff provides the following settings:

* `allowSingleLineYieldStacking`: whether or not to allow multiple yield/yield from statements in a row without blank lines.
* `linesCountBeforeControlStructure`: allows to configure the number of lines before jump statement.
* `linesCountBeforeFirstControlStructure`: allows to configure the number of lines before first jump statement.
* `linesCountBeforeWhenFirstInCaseOrDefault`: allows to configure the number of lines before jump statement that is first in `case` or `default`
* `linesCountAfterControlStructure`: allows to configure the number of lines after jump statement.
* `linesCountAfterLastControlStructure`: allows to configure the number of lines after last jump statement.
* `linesCountAfterWhenLastInCaseOrDefault`: allows to configure the number of lines after jump statement that is last in `case` or `default`
* `linesCountAfterWhenLastInLastCaseOrDefault`: allows to configure the number of lines after jump statement that is last in last `case` or `default`
* `tokensToCheck`: allows to narrow the list of checked tokens.

For example, with the following setting, only `continue` and `break` tokens are checked.

```xml
<rule ref="SlevomatCodingStandard.ControlStructures.JumpStatementsSpacing">
	<properties>
		<property name="tokensToCheck" type="array">
			<element value="T_CONTINUE"/>
			<element value="T_BREAK"/>
		</property>
	</properties>
</rule>
```

#### SlevomatCodingStandard.ControlStructures.LanguageConstructWithParentheses 🔧

`LanguageConstructWithParenthesesSniff` checks and fixes language construct used with parentheses.

#### SlevomatCodingStandard.ControlStructures.NewWithParentheses 🔧

Requires `new` with parentheses.

#### SlevomatCodingStandard.ControlStructures.NewWithoutParentheses 🔧

Reports `new` with useless parentheses.

#### SlevomatCodingStandard.ControlStructures.DisallowShortTernaryOperator 🔧

Disallows short ternary operator `?:`.

Sniff provides the following settings:

* `fixable`: the sniff is fixable by default, however in strict code it makes sense to forbid this weakly typed form of ternary altogether, you can disable fixability with this option.

#### SlevomatCodingStandard.ControlStructures.RequireMultiLineTernaryOperator 🔧

Ternary operator has to be reformatted to more lines when the line length exceeds the given limit.

Sniff provides the following settings:

* `lineLengthLimit` (defaults to `0`)

#### SlevomatCodingStandard.ControlStructures.RequireSingleLineCondition 🔧

Enforces conditions of `if`, `elseif`, `while` and `do-while` to be on a single line.

Sniff provides the following settings:

* `maxLineLength`: specifies max allowed line length. If conditition (and the rest of the line) would fit on it, it's enforced. Use 0 value to enforce for all conditions, regardless of length.
* `alwaysForSimpleConditions`: allows to enforce single line for all simple conditions (i.e no `&&`, `||` or `xor`), regardless of length.

#### SlevomatCodingStandard.ControlStructures.RequireMultiLineCondition 🔧

Enforces conditions of `if`, `elseif`, `while` and `do-while` with one or more boolean operators to be splitted to more lines
so each condition part is on its own line.

Sniff provides the following settings:

* `minLineLength`: specifies mininum line length to enforce condition to be splitted. Use 0 value to enforce for all conditions, regardless of length.
* `booleanOperatorOnPreviousLine`: boolean operator is placed at the end of previous line when fixing.
* `alwaysSplitAllConditionParts`: require all condition parts to be on its own line - it reports error even if condition is already multi-line but there are some condition parts on the same line.

#### SlevomatCodingStandard.ControlStructures.RequireShortTernaryOperator 🔧

Requires short ternary operator `?:` when possible.

#### SlevomatCodingStandard.ControlStructures.RequireTernaryOperator 🔧

Requires ternary operator when possible.

Sniff provides the following settings:

* `ignoreMultiLine` (defaults to `false`): ignores multi-line statements.

#### SlevomatCodingStandard.ControlStructures.DisallowYodaComparison/RequireYodaComparison 🔧

[Yoda conditions](https://en.wikipedia.org/wiki/Yoda_conditions) decrease code comprehensibility and readability by switching operands around comparison operators forcing the reader to read the code in an unnatural way.

Sniff provides the following settings:

* `alwaysVariableOnRight` (defaults to `false`): moves variables always to right.

`DisallowYodaComparisonSniff` looks for and fixes such comparisons not only in `if` statements but in the whole code.

However, if you prefer Yoda conditions, you can use `RequireYodaComparisonSniff`.

#### SlevomatCodingStandard.Files.LineLength

Enforces maximum length of a single line of code.

Sniff provides the following settings:

* `lineLengthLimit`: actual limit of the line length
* `ignoreComments`: whether or not to ignore line length of comments
* `ignoreImports`: whether or not to ignore line length of import (use) statements

#### SlevomatCodingStandard.Functions.ArrowFunctionDeclaration

Checks `fn` declaration.

Sniff provides the following settings:

* `spacesCountAfterKeyword`: the number of spaces after `fn`.
* `spacesCountBeforeArrow`: the number of spaces before `=>`.
* `spacesCountAfterArrow`: the number of spaces after `=>`.
* `allowMultiLine`: allows multi-line declaration.

#### SlevomatCodingStandard.Functions.DisallowEmptyFunction

Reports empty functions body and requires at least a comment inside.

#### SlevomatCodingStandard.Functions.DisallowArrowFunction

Disallows arrow functions.

#### SlevomatCodingStandard.Functions.RequireArrowFunction 🔧

Requires arrow functions.

Sniff provides the following settings:

* `allowNested` (defaults to `true`)
* `enable`: either to enable or no this sniff. By default, it is enabled for PHP versions 7.4 or higher.

#### SlevomatCodingStandard.Functions.RequireMultiLineCall 🔧

Enforces function call to be splitted to more lines so each parameter is on its own line.

Sniff provides the following settings:

* `minLineLength`: specifies min line length to enforce call to be splitted. Use 0 value to enforce for all calls, regardless of length.

#### SlevomatCodingStandard.Functions.RequireSingleLineCall 🔧

Enforces function call to be on a single line.

Sniff provides the following settings:

* `maxLineLength`: specifies max allowed line length. If call would fit on it, it's enforced. Use 0 value to enforce for all calls, regardless of length.
* `ignoreWithComplexParameter` (defaults to `true`): ignores calls with arrays, closures, arrow functions and nested calls.

#### SlevomatCodingStandard.Functions.TrailingCommaInCall 🔧

Commas after the last parameter in function or method call make adding a new parameter easier and result in a cleaner versioning diff.

This sniff enforces trailing commas in multi-line calls.

This sniff provides the following setting:

* `enable`: either to enable or no this sniff. By default, it is enabled for PHP versions 7.3 or higher.

#### SlevomatCodingStandard.Namespaces.AlphabeticallySortedUses 🔧

Checks whether uses at the top of a file are alphabetically sorted. Follows natural sorting and takes edge cases with special symbols into consideration. The following code snippet is an example of correctly sorted uses:

```php
use LogableTrait;
use LogAware;
use LogFactory;
use LoggerInterface;
use LogLevel;
use LogStandard;
```

Sniff provides the following settings:


* `psr12Compatible` (defaults to `true`): sets the required order to `classes`, `functions` and `constants`. `false` sets the required order to `classes`, `constants` and `functions`.
* `caseSensitive`: compare namespaces case sensitively, which makes this order correct:

```php
use LogAware;
use LogFactory;
use LogLevel;
use LogStandard;
use LogableTrait;
use LoggerInterface;
```

#### SlevomatCodingStandard.Namespaces.RequireOneNamespaceInFile

Requires only one namespace in a file.

#### SlevomatCodingStandard.Namespaces.NamespaceDeclaration 🔧

Enforces one space after `namespace`, disallows content between namespace name and semicolon and disallows use of bracketed syntax.

#### SlevomatCodingStandard.Namespaces.NamespaceSpacing 🔧

Enforces configurable number of lines before and after `namespace`.

Sniff provides the following settings:

* `linesCountBeforeNamespace`: allows to configure the number of lines before `namespace`.
* `linesCountAfterNamespace`: allows to configure the number of lines after `namespace`.

#### SlevomatCodingStandard.Namespaces.UseSpacing 🔧

Enforces configurable number of lines before first `use`, after last `use` and between two different types of `use` (eg. between `use function` and `use const`). Also enforces zero number of lines between same types of `use`.

Sniff provides the following settings:

* `linesCountBeforeFirstUse`: allows to configure the number of lines before first `use`.
* `linesCountBetweenUseTypes`: allows to configure the number of lines between two different types of `use`.
* `linesCountAfterLastUse`: allows to configure the number of lines after last `use`.

#### SlevomatCodingStandard.Numbers.DisallowNumericLiteralSeparator 🔧

Disallows numeric literal separators.

#### SlevomatCodingStandard.Numbers.RequireNumericLiteralSeparator

Requires use of numeric literal separators.

This sniff provides the following setting:

* `enable`: either to enable or no this sniff. By default, it is enabled for PHP versions 7.4 or higher.
* `minDigitsBeforeDecimalPoint`: the mininum digits before decimal point to require separator.
* `minDigitsAfterDecimalPoint`: the mininum digits after decimal point to require separator.

#### SlevomatCodingStandard.PHP.ReferenceSpacing 🔧

Enforces configurable number of spaces after reference.

Sniff provides the following settings:

* `spacesCountAfterReference`: the number of spaces after `&`.

#### SlevomatCodingStandard.Operators.NegationOperatorSpacing 🔧

Checks if there is the same number of spaces after negation operator as expected.

Sniff provides the following settings:

* `spacesCount`: the number of spaces expected after the negation operator

#### SlevomatCodingStandard.Operators.SpreadOperatorSpacing 🔧

Enforces configurable number of spaces after the `...` operator.

Sniff provides the following settings:

* `spacesCountAfterOperator`: the number of spaces after the `...` operator.

#### SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax 🔧

Disallows usage of array type hint syntax (eg. `int[]`, `bool[][]`) in phpDocs in favour of generic type hint syntax (eg. `array<int>`, `array<array<bool>>`).

Sniff provides the following settings:

* `traversableTypeHints`: helps fixer detect traversable type hints so `\Traversable|int[]` can be converted to `\Traversable<int>`.

#### SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint

Disallows usage of "mixed" type hint in phpDocs.

#### SlevomatCodingStandard.TypeHints.LongTypeHints 🔧

Enforces using shorthand scalar typehint variants in phpDocs: `int` instead of `integer` and `bool` instead of `boolean`. This is for consistency with native scalar typehints which also allow shorthand variants only.

#### SlevomatCodingStandard.TypeHints.NullTypeHintOnLastPosition 🔧

Enforces `null` type hint on last position in annotations.


#### SlevomatCodingStandard.PHP.ShortList 🔧

Enforces using short form of list syntax, `[...]` instead of `list(...)`.


#### SlevomatCodingStandard.PHP.TypeCast 🔧

Enforces using shorthand cast operators, forbids use of unset and binary cast operators: `(bool)` instead of `(boolean)`, `(int)` instead of `(integer)`, `(float)` instead of `(double)` or `(real)`. `(binary)` and `(unset)` are forbidden.

#### SlevomatCodingStandard.Whitespaces.DuplicateSpaces 🔧

Checks duplicate spaces anywhere because there aren't sniffs for every part of code to check formatting.

Sniff provides the following settings:

* `ignoreSpacesBeforeAssignment`: to allow multiple spaces to align assignments.
* `ignoreSpacesInAnnotation`: to allow multiple spaces to align annotations.
* `ignoreSpacesInComment`: to allow multiple spaces to align content of the comment.
* `ignoreSpacesInParameters`: to allow multiple spaces to align parameters.

#### SlevomatCodingStandard.Files.TypeNameMatchesFileName

For projects not following the [PSR-0](http://www.php-fig.org/psr/psr-0/) or [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading standards, this sniff checks whether a namespace and a name of a class/interface/trait follows agreed-on way to organize code into directories and files.

Other than enforcing that the type name must match the name of the file it's contained in, this sniff is very configurable. Consider the following sample configuration:

```xml
<rule ref="SlevomatCodingStandard.Files.TypeNameMatchesFileName">
	<properties>
		<property name="rootNamespaces" type="array">
			<element key="app/ui" value="Slevomat\UI"/>
			<element key="app" value="Slevomat"/>
			<element key="build/SlevomatSniffs/Sniffs" value="SlevomatSniffs\Sniffs"/>
			<element key="tests/ui" value="Slevomat\UI"/>
			<element key="tests" value="Slevomat"/>
		</property>
		<property name="skipDirs" type="array">
			<element value="components"/>
			<element value="forms"/>
			<element value="model"/>
			<element value="models"/>
			<element value="services"/>
			<element value="stubs"/>
			<element value="data"/>
			<element value="new"/>
		</property>
		<property name="ignoredNamespaces" type="array">
			<element value="Slevomat\Services"/>
		</property>
	</properties>
</rule>
```

Sniff provides the following settings:

* `rootNamespaces` property expects configuration similar to PSR-4 - project directories mapped to certain namespaces.
* `skipDirs` are not taken into consideration when comparing a path to a namespace. For example, with the above settings, file at path `app/services/Product/Product.php` is expected to contain `Slevomat\Product\Product`, not `Slevomat\services\Product\Product`.
* `extensions`: allow different file extensions. Default is `php`.
* `ignoredNamespaces`: sniff is not performed on these namespaces.

#### SlevomatCodingStandard.Classes.ClassConstantVisibility 🔧

In PHP 7.1+ it's possible to declare [visibility of class constants](https://wiki.php.net/rfc/class_const_visibility). In a similar vein to optional declaration of visibility for properties and methods which is actually required in sane coding standards, this sniff also requires declaring visibility for all class constants.

Sniff provides the following settings:

* `fixable`: the sniff is not fixable by default because we think it's better to decide about each constant one by one however you can enable fixability with this option.

```php
const FOO = 1; // visibility missing!
public const BAR = 2; // correct
```

#### SlevomatCodingStandard.TypeHints.ReturnTypeHintSpacing 🔧

Enforces consistent formatting of return typehints, like this:

```php
function foo(): ?int
```

Sniff provides the following settings:

* `spacesCountBeforeColon`: the number of spaces expected between closing brace and colon.

#### SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue 🔧🚧

Checks whether the nullablity `?` symbol is present before each nullable and optional parameter (which are marked as `= null`):

```php
function foo(
	int $foo = null, // ? missing
	?int $bar = null // correct
) {

}
```

#### SlevomatCodingStandard.TypeHints.ParameterTypeHintSpacing 🔧

* Checks that there's a single space between a typehint and a parameter name: `Foo $foo`
* Checks that there's no whitespace between a nullability symbol and a typehint: `?Foo`

#### SlevomatCodingStandard.TypeHints.PropertyTypeHintSpacing 🔧

* Checks that there's a single space between a typehint and a property name: `Foo $foo`
* Checks that there's no whitespace between a nullability symbol and a typehint: `?Foo`
* Checks that there's a single space before nullability symbol or a typehint: `private ?Foo` or `private Foo`

#### SlevomatCodingStandard.Namespaces.DisallowGroupUse

[Group use declarations](https://wiki.php.net/rfc/group_use_declarations) are ugly, make diffs ugly and this sniff prohibits them.

#### SlevomatCodingStandard.Namespaces.FullyQualifiedClassNameAfterKeyword 🔧

Enforces fully qualified type references after configurable set of language keywords.

For example, with the following setting, extended or implemented type must always be referenced with a fully qualified name:

```xml
<rule ref="SlevomatCodingStandard.Namespaces.FullyQualifiedClassNameAfterKeyword">
	<properties>
		<property name="keywordsToCheck" type="array">
			<element value="T_EXTENDS"/>
			<element value="T_IMPLEMENTS"/>
		</property>
	</properties>
</rule>
```

#### SlevomatCodingStandard.Namespaces.FullyQualifiedExceptions 🔧

This sniff reduces confusion in the following code snippet:

```php
try {
	$this->foo();
} catch (Exception $e) {
	// Is this the general exception all exceptions must extend from? Or Exception from the current namespace?
}
```

All references to types named `Exception` or ending with `Exception` must be referenced via a fully qualified name:

```php
try {
	$this->foo();
} catch (\FooCurrentNamespace\Exception $e) {

} catch (\Exception $e) {

}
```

Sniff provides the following settings:

* Exceptions with different names can be configured in `specialExceptionNames` property.
* If your codebase uses classes that look like exceptions (because they have `Exception` or `Error` suffixes) but aren't, you can add them to `ignoredNames` property and the sniff won't enforce them to be fully qualified. Classes with `Error` suffix has to be added to ignored only if they are in the root namespace (like `LibXMLError`).

#### SlevomatCodingStandard.Namespaces.FullyQualifiedGlobalConstants 🔧

All references to global constants must be referenced via a fully qualified name.

Sniff provides the following settings:

* `include`: list of global constants that must be referenced via FQN. If not set all constants are considered.
* `exclude`: list of global constants that are allowed not to be referenced via FQN.

#### SlevomatCodingStandard.Namespaces.FullyQualifiedGlobalFunctions 🔧

All references to global functions must be referenced via a fully qualified name.

Sniff provides the following settings:

* `include`: list of global functions that must be referenced via FQN. If not set all functions are considered.
* `includeSpecialFunctions`: include complete list of PHP internal functions that could be optimized when referenced via FQN.
* `exclude`: list of global functions that are allowed not to be referenced via FQN.

#### SlevomatCodingStandard.Namespaces.MultipleUsesPerLine

Prohibits multiple uses separated by commas:

```php
use Foo, Bar;
```

#### SlevomatCodingStandard.Namespaces.ReferenceUsedNamesOnly 🔧

Sniff provides the following settings:

* `searchAnnotations` (defaults to `false`): enables searching for mentions in annotations.
* `namespacesRequiredToUse`: if not set, all namespaces are required to be used. When set, only mentioned namespaces are required to be used. Useful in tandem with UseOnlyWhitelistedNamespaces sniff.
* `fullyQualifiedKeywords`: allows fully qualified names after certain keywords. Useful in tandem with FullyQualifiedClassNameAfterKeyword sniff.
* `allowFullyQualifiedExceptions`, `specialExceptionNames` & `ignoredNames`: allows fully qualified exceptions. Useful in tandem with FullyQualifiedExceptions sniff.
* `allowFullyQualifiedNameForCollidingClasses`: allow fully qualified name for a class with a colliding use statement.
* `allowFullyQualifiedNameForCollidingFunctions`: allow fully qualified name for a function with a colliding use statement.
* `allowFullyQualifiedNameForCollidingConstants`: allow fully qualified name for a constant with a colliding use statement.
* `allowFullyQualifiedGlobalClasses`: allows using fully qualified classes from global space (i.e. `\DateTimeImmutable`).
* `allowFullyQualifiedGlobalFunctions`: allows using fully qualified functions from global space (i.e. `\phpversion()`).
* `allowFullyQualifiedGlobalConstants`: allows using fully qualified constants from global space (i.e. `\PHP_VERSION`).
* `allowFallbackGlobalFunctions`: allows using global functions via fallback name without `use` (i.e. `phpversion()`).
* `allowFallbackGlobalConstants`: allows using global constants via fallback name without `use` (i.e. `PHP_VERSION`).
* `allowPartialUses`: allows using and referencing whole namespaces:

#### SlevomatCodingStandard.Namespaces.UseOnlyWhitelistedNamespaces

Disallows uses of other than configured namespaces.

Sniff provides the following settings:

* `namespacesRequiredToUse`: namespaces in this array are the only ones allowed to be used. E. g. root project namespace.
* `allowUseFromRootNamespace`: also allow using top-level namespace:

```php
use DateTimeImmutable;
```

#### SlevomatCodingStandard.Namespaces.UseDoesNotStartWithBackslash 🔧

Disallows leading backslash in use statement:

```php
use \Foo\Bar;
```

#### SlevomatCodingStandard.Classes.EmptyLinesAroundClassBraces 🔧

Enforces one configurable number of lines after opening class/interface/trait brace and one empty line before the closing brace.

Sniff provides the following settings:

* `linesCountAfterOpeningBrace`: allows to configure the number of lines after opening brace.
* `linesCountBeforeClosingBrace`: allows to configure the number of lines before closing brace.

#### SlevomatCodingStandard.Namespaces.FullyQualifiedClassNameInAnnotation 🔧

Enforces fully qualified names of classes and interfaces in phpDocs - in annotations. This results in unambiguous phpDocs.

#### SlevomatCodingStandard.Commenting.DeprecatedAnnotationDeclarationSniff

Reports `@deprecated` annotations without description.

#### SlevomatCodingStandard.Commenting.DisallowCommentAfterCode 🔧

Disallows comments after code at the same line.

#### SlevomatCodingStandard.Commenting.ForbiddenAnnotations 🔧

Reports forbidden annotations. No annotations are forbidden by default, the configuration is completely up to the user. It's recommended to forbid obsolete and inappropriate annotations like:

* `@author`, `@created`, `@version`: we have version control systems.
* `@package`: we have namespaces.
* `@copyright`, `@license`: it's not necessary to repeat licensing information in each file.
* `@throws`: it's not possible to enforce this annotation and the information can become outdated.

Sniff provides the following settings:

* `forbiddenAnnotations`: allows to configure which annotations are forbidden to be used.

#### SlevomatCodingStandard.Commenting.ForbiddenComments 🔧

Reports forbidden comments in descriptions. Nothing is forbidden by default, the configuration is completely up to the user. It's recommended to forbid generated or inappropriate messages like:

* `Constructor.`
* `Created by PhpStorm.`

Sniff provides the following settings:

* `forbiddenCommentPatterns`: allows to configure which comments are forbidden to be used. This is an array of regular expressions (PCRE) with delimiters.

#### SlevomatCodingStandard.Commenting.DocCommentSpacing 🔧

Enforces configurable number of lines before first content (description or annotation), after last content (description or annotation),
between description and annotations, between two different annotations types (eg. between `@param` and `@return`).

Sniff provides the following settings:

* `linesCountBeforeFirstContent`: allows to configure the number of lines before first content (description or annotation).
* `linesCountBetweenDescriptionAndAnnotations`: allows to configure the number of lines between description and annotations.
* `linesCountBetweenDifferentAnnotationsTypes`: allows to configure the number of lines between two different annotations types.
* `linesCountBetweenAnnotationsGroups`: allows to configure the number of lines between annotations groups.
* `linesCountAfterLastContent`: allows to configure the number of lines after last content (description or annotation).
* `annotationsGroups`: allows to configurure order of annotations groups and even order of annotations in every group. Supports prefixes, eg. `@ORM\`.

```xml
<rule ref="SlevomatCodingStandard.Commenting.DocCommentSpacing">
	<properties>
		<property name="annotationsGroups" type="array">
			<element value="
				@ORM\,
			"/>
			<element value="
				@var,
				@param,
				@return,
			"/>
		</property>
	</properties>
</rule>
```

If `annotationsGroups` is set, `linesCountBetweenDifferentAnnotationsTypes` is ignored and `linesCountBetweenAnnotationsGroups` is applied.
If `annotationsGroups` is not set, `linesCountBetweenAnnotationsGroups` is ignored and `linesCountBetweenDifferentAnnotationsTypes` is applied.

Annotations not in any group are placed to automatically created last group.

#### SlevomatCodingStandard.Commenting.EmptyComment 🔧

Reports empty comments.

#### SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration 🔧

Reports invalid inline phpDocs with `@var`.

Sniff provides the following settings:

* `allowDocCommentAboveReturn`: Allows documentation comments without variable name above `return` statemens.
* `allowAboveNonAssignment`: Allows documentation comments above non-assigment if the line contains the right variable name.

#### SlevomatCodingStandard.Commenting.RequireOneLinePropertyDocComment 🔧

Requires property comments with single-line content to be written as one-liners.

#### SlevomatCodingStandard.Commenting.RequireOneLineDocComment 🔧

Requires comments with single-line content to be written as one-liners.

#### SlevomatCodingStandard.Commenting.DisallowOneLinePropertyDocComment 🔧

Requires comments with single-line content to be written as multi-liners.

#### SlevomatCodingStandard.Commenting.UselessFunctionDocComment 🔧🚧

* Checks for useless doc comments. If the native method declaration contains everything and the phpDoc does not add anything useful, it's reported as useless and can optionally be automatically removed with `phpcbf`.
* Some phpDocs might still be useful even if they do not add any typehint information. They can contain textual descriptions of code elements and also some meaningful annotations like `@expectException` or `@dataProvider`.

Sniff provides the following settings:

* `traversableTypeHints`: enforces which typehints must have specified contained type. E. g. if you set this to `\Doctrine\Common\Collections\Collection`, then `\Doctrine\Common\Collections\Collection` must always be supplied with the contained type: `\Doctrine\Common\Collections\Collection|Foo[]`.

This sniff can cause an error if you're overriding or implementing a parent method which does not have typehints. In such cases add `@phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint` annotation to the method to have this sniff skip it.

#### SlevomatCodingStandard.Commenting.UselessInheritDocComment 🔧

Reports documentation comments containing only `{@inheritDoc}` annotation because inheritance is automatic and it's not needed to use a special annotation for it.

#### SlevomatCodingStandard.ControlStructures.UselessIfConditionWithReturn 🔧

Reports useless conditions where both branches return `true` or `false`.

Sniff provides the following settings:

* `assumeAllConditionExpressionsAreAlreadyBoolean` (defaults to `false`).

#### SlevomatCodingStandard.ControlStructures.UselessTernaryOperator 🔧

Reports useless ternary operator where both branches return `true` or `false`.

Sniff provides the following settings:

* `assumeAllConditionExpressionsAreAlreadyBoolean` (defaults to `false`).

## Installation

The recommended way to install Slevomat Coding Standard is [through Composer](http://getcomposer.org).

```JSON
{
	"require-dev": {
		"slevomat/coding-standard": "~6.0"
	}
}
```

It's also recommended to install [php-parallel-lint/php-parallel-lint](https://github.com/php-parallel-lint/PHP-Parallel-Lint) which checks source code for syntax errors. Sniffs count on the processed code to be syntactically valid (no parse errors), otherwise they can behave unexpectedly. It is advised to run `PHP-Parallel-Lint` in your build tool before running `PHP_CodeSniffer` and exiting the build process early if `PHP-Parallel-Lint` fails.

## How to run the sniffs

You can choose one of two ways to run only selected sniffs from the standard on your codebase:

### Choose which sniffs to run

The recommended way is to write your own ruleset.xml by referencing only the selected sniffs. This is a sample ruleset.xml:

```xml
<?xml version="1.0"?>
<ruleset name="AcmeProject">
	<config name="installed_paths" value="../../slevomat/coding-standard"/><!-- relative path from PHPCS source location -->
	<rule ref="SlevomatCodingStandard.Arrays.TrailingArrayComma"/>
	<!-- other sniffs to include -->
</ruleset>
```

Then run the `phpcs` executable the usual way:

```
vendor/bin/phpcs --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests
```

### Exclude sniffs you don't want to run

You can also mention Slevomat Coding Standard in your project's `ruleset.xml` and exclude only some sniffs:

```xml
<?xml version="1.0"?>
<ruleset name="AcmeProject">
	<rule ref="vendor/slevomat/coding-standard/SlevomatCodingStandard/ruleset.xml"><!-- relative path to your ruleset.xml -->
		<!-- sniffs to exclude -->
	</rule>
</ruleset>
```

However it is not a recommended way to use Slevomat Coding Standard, because your build can break when moving between minor versions of the standard (which can happen if you use `^` or `~` version constraint in `composer.json`). We regularly add new sniffs even in minor versions meaning your code won't most likely comply with new minor versions of the package.

## Fixing errors automatically

Sniffs in this standard marked by the 🔧 symbol support [automatic fixing of coding standard violations](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Fixing-Errors-Automatically). To fix your code automatically, run phpcbf instead of phpcs:

```
vendor/bin/phpcbf --standard=ruleset.xml --extensions=php --tab-width=4 -sp src tests
```

Always remember to back up your code before performing automatic fixes and check the results with your own eyes as the automatic fixer can sometimes produce unwanted results.

## Suppressing sniffs locally

Selected sniffs in this standard marked by the 🚧 symbol can be suppressed for a specific piece of code using an annotation. Consider the following example:

```php
/**
 * @param int $max
 */
public function createProgressBar($max = 0): ProgressBar
{

}
```

The parameter `$max` could have a native `int` scalar typehint. But because the method in the parent class does not have this typehint, so this one cannot have it either. PHP_CodeSniffer shows a following error:

```
----------------------------------------------------------------------
FOUND 1 ERROR AFFECTING 1 LINE
----------------------------------------------------------------------
 67 | ERROR | [x] Method ErrorsConsoleStyle::createProgressBar()
    |       |     does not have native type hint for its parameter $max
    |       |     but it should be possible to add it based on @param
    |       |     annotation "int".
    |       |     (SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint)
```

If we want to suppress this error instead of fixing it, we can take the error code (`SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint`) and use it with a `@phpcsSuppress` annotation like this:

```php
/**
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
 * @param int $max
 */
public function createProgressBar($max = 0): ProgressBar
{

}
```

## Contributing

To make this repository work on your machine, clone it and run these two commands in the root directory of the repository:

```
composer install
bin/phing
```

After writing some code and editing or adding unit tests, run phing again to check that everything is OK:

```
bin/phing
```

We are always looking forward for your bugreports, feature requests and pull requests. Thank you.

## Code of Conduct

This project adheres to a [Contributor Code of Conduct](https://github.com/slevomat/coding-standard/blob/master/CODE_OF_CONDUCT.md). By participating in this project and its community, you are expected to uphold this code.
