<html>
<head>
<title>Weather Condition</title>
</head>

<body>
<h1>Weather Condition <?php print(date('m/d/Y')); ?></h1>
<table width="100%">
  <tr>
    <td><div align="center"><img width="480" height="360" src="graph.php?Date=<?php print(date('Y-m-d')); ?>&Type=Temperature"></div></td>
    <td><div align="center"><img width="480" height="360" src="graph.php?Date=<?php print(date('Y-m-d')); ?>&Type=DewPoint"></div></td>
  </tr>
  <tr>
    <td><div align="center"><img width="480" height="360" src="graph.php?Date=<?php print(date('Y-m-d')); ?>&Type=RelativeHumidity"></div></td>
    <td><div align="center"><img width="480" height="360" src="graph.php?Date=<?php print(date('Y-m-d')); ?>&Type=Pressure"></div></td>
  </tr>
  <tr>
    <td><div align="center"><img width="480" height="360" src="graph.php?Date=<?php print(date('Y-m-d')); ?>&Type=LightTEMT6000"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
