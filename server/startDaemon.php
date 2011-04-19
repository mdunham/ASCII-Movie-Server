<?php
header('Content-Type: text/html');
header('Cache-Control: no-cache');

$frames = array('','','','','','','','','','');

$len = strlen(sha1(md5('test')));

for ($frame = 0; $frame < 10; $frame++) {
	$frames[$frame] .= 'eeeIsssC}}ooo}onnnnnuoonuuuuuTnonTuuTT
XXkeIzsszzzzC}VUUAhIo}fnTTTlTlufuTTTTl
zzs}}}CfnnnzZN@@@@@@Ndffnnuunufnnnnnuu
s}}oooofuu2W@#NMNM0HR@$C777777luTllttY
}}ooffoouAW6b4aXsoTluFN$T77777luTlll77
}oofnnnuu9UlLycccyxLYuZ#h77t7tlTnl7777
zzzC}ouffR}Yxc***yxLYlXN4tttttlu777777
onofuuuulbUtLyc**cJffff2stuT7tTut77777
CfuuTuTulTOuuozJyLzeI}7nz7ttTuuTuuuuuu
onTTTTln77}}eV}7xLtiyxJ7olTtt7ul77t777
}nTllTll77t7iyciiilYyxitnHWE#MRE$90PGX
fnoouu7TTll7LyyiYJJJYLiYn4@WW888##MNNR
nTllll7lt7ltYiiJtYYtLxxtCG@WWW88####ME
fTll77lT7tt7aniixyyyiYuznU@WWW88M#M#MM
Tl777777tYI68MdztLLtfszTtd@W888W###MMM
}ulll77to5###WW84oCzoTttk8W8##8W#88M#M
2GUhVsz2NM#8MW8W@5zTtlsb8#W#8888888M##
dZKm22DEN#M8M8#8WW#9Q#W8M8#M8#8888W88#
OSbdK3ENMM###8#8#######MM##M###888WW88
5SPPb6ENM###M8##88M#M#MMM##MN###W8W@@W
A4mZPRM#M##MM8#M8####MMMM##MMMMM$K#W@@
FkXVFE##M###M8#M###MM#MMM###MN3CTCR8@@
';
}

echo json_encode($frames);

exit();