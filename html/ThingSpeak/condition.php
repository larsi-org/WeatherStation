<html>
<head>
<title>Weather Condition</title>
</head>

<body>
<h1>Weather Condition <?php print(date('m/d/Y')); ?></h1>
<table width="100%">
  <tr>
    <td><div align="center"><iframe width="480" height="250" style="border: 1px solid #cccccc;" src="https://www.thingspeak.com/channels/346/charts/1?timescale=10"></iframe></div></td>
    <td><div align="center"><iframe width="480" height="250" style="border: 1px solid #cccccc;" src="https://www.thingspeak.com/channels/346/charts/2?timescale=10"></iframe></div></td>
  </tr>
  <tr>
    <td><div align="center"><iframe width="480" height="250" style="border: 1px solid #cccccc;" src="https://www.thingspeak.com/channels/346/charts/3?timescale=10"></iframe></div></td>
    <td><div align="center"><iframe width="480" height="250" style="border: 1px solid #cccccc;" src="https://www.thingspeak.com/channels/346/charts/4?timescale=10"></iframe></div></td>
  </tr>
  <tr>
    <td><div align="center"><iframe width="480" height="250" style="border: 1px solid #cccccc;" src="https://www.thingspeak.com/channels/346/charts/5?timescale=10"></iframe></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
