# number2text
Creates textual equivalent of given numbers which as long as 450 digits (in Turkish)

450 basamaga kadar olan sayilarin turkce olarak metinsel okunusunu verir

### Usage
$n2t = new Number2Text(3001001);

echo $n2t->textual();   // üç milyon bin bir

### Usage (Negative)
$n2t = new Number2Text(-3001001);

echo $n2t->textual();   // eksi üç milyon bin bir
