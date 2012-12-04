PHP Unicode Data Toolkit                                            {#mainpage}
========================

Toolkit for reading, writing and transforming unicode data tables.

This project provides a set of classes simplifying parsing and manipulation of
files formatted according to the [UAX #44 File Format Conventions] including
[UnicodeData.txt] and [PropList.txt]. It contains algorithms capable of
calculating the _difference_ and _union_ of codepoint ranges necessary to
implement the algorithm used to calculate derived properties as described in
the [Precis Framework] or [RFC5892]. Further there are classes helping with
construction of PCRE compatible [character classes] such that the result of
those transformations can be easily serialized and reused as part of other
projects.

Examples
--------

Refer to the _examples_ directory of the project source code for guidance on
how to build your tools using the PHP Unicode Data Toolkit.

Source Code
-----------

The source code is hosted on github:

    git clone git://github.com/znerol/php-ucd-tk.git
    cd php-ucd-tk

An issue tracker and the wiki page is available at
https://github.com/znerol/php-ucd-tk


API Documentation
-----------------

See http://znerol.github.com/php-ucd-tk/doc/api/html

License
-------

This project is licensed under the terms of the MIT License.

[UAX #44 File Format Conventions]:
  http://www.unicode.org/reports/tr44/#Format_Conventions
[UnicodeData.txt]:
  http://www.unicode.org/Public/UNIDATA/UnicodeData.txt
[PropList.txt]:
  http://www.unicode.org/Public/UNIDATA/PropList.txt
[Precis Framework]:
  http://tools.ietf.org/html/draft-ietf-precis-framework-06#section-7
  "Precis Framework IETF Draft 6 - Section 7"
[RFC5892]:
  http://tools.ietf.org/html/rfc5892#section-3
  "RFC5892 - Section 3"
[character classes]:
  http://php.net/manual/en/regexp.reference.character-classes.php
