//
// Hoa
//
//
// @license
//
// New BSD License
//
// Copyright © 2007-2017, Hoa community. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are met:
//     * Redistributions of source code must retain the above copyright
//       notice, this list of conditions and the following disclaimer.
//     * Redistributions in binary form must reproduce the above copyright
//       notice, this list of conditions and the following disclaimer in the
//       documentation and/or other materials provided with the distribution.
//     * Neither the name of the Hoa nor the names of its contributors may be
//       used to endorse or promote products derived from this software without
//       specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
// AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
// LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
// CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
// SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
// INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
// CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.
//
// Grammar \Hoa\Compiler\Llk.
//
// Provide grammar for the LL(k) parser.
//
// @copyright  Copyright © 2007-2017, Hoa community.
// @license    New BSD License
//


%skip   space          \s

%token  or             \|
%token  zero_or_one    \?
%token  one_or_more    \+
%token  zero_or_more   \*
%token  n_to_m         \{[0-9]+,[0-9]+\}
%token  zero_to_m      \{,[0-9]+\}
%token  n_or_more      \{[0-9]+,\}
%token  exactly_n      \{[0-9]+\}

%token  token          [a-zA-Z_][a-zA-Z0-9_]*

%token  skipped        ::
%token  kept_          <
%token _kept           >
%token  named          \(\)
%token  node           #[a-zA-Z_][a-zA-Z0-9_]*(:[mM])?

%token  capturing_     \(
%token _capturing      \)
%token  unification_   \[
%token  unification    [0-9]+
%token _unification    \]

#rule:
    choice()

choice:
    concatenation() ( ::or:: concatenation() #choice )*

concatenation:
    repetition() ( repetition() #concatenation )*

repetition:
    simple() ( quantifier() #repetition )? <node>?

simple:
    ::capturing_:: choice() ::_capturing::
  | ::skipped:: <token> ( ::unification_:: <unification> ::_unification:: )?
    ::skipped:: #skipped
  | ::kept_:: <token> ( ::unification_:: <unification> ::_unification:: )?
    ::_kept:: #kept
  | <token> ::named::

quantifier:
    <zero_or_one>
  | <one_or_more>
  | <zero_or_more>
  | <n_to_m>
  | <n_or_more>
  | <exactly_n>
