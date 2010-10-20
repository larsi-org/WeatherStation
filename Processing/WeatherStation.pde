/*
  This is a basic serial arduinoscope.
 
 (c) 2009 David Konsumer <david.konsumer@gmail.com>
 
 This library is free software; you can redistribute it and/or
 modify it under the terms of the GNU Lesser General Public
 License as published by the Free Software Foundation; either
 version 2.1 of the License, or (at your option) any later version.
 
 This library is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 Lesser General Public License for more details.
 
 You should have received a copy of the GNU Lesser General
 Public License along with this library; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330,
 Boston, MA  02111-1307  USA
 */

import processing.serial.*;

// Logging server
String SERVER   = "http://make.larsi.org/";

// Station location
String LOCATION = "LivingRoom";

// Station channels
String labels[] = { "Temperature", "RelativeHumidity", "Pressure", "LightTEMT6000", "DewPoint" };
float  mins[]   = {            50,                  0,        950,               0,         50 };
float  maxs[]   = {           100,                100,       1050,            1023,        100 };
float  m[]      = {           1.8,                  1,       0.01,               1,        1.8 };
float  n[]      = {            32,                  0,          0,               0,         32 };

Channel channels[] = new Channel[labels.length];

Serial port;

int LINE_FEED=10; 

// setup vals from serial
float[] vals = new float[labels.length];
long currentID = 0;
long lastID    = 2; // skip first two values
String lastLog = "";

void setup()
{
  size(1080, 800, P2D);
  frameRate(1);
  background(0);

  // set these up under tools/create font, if they are not setup.
  textFont(loadFont("TrebuchetMS-20.vlw"));

  int dimX = width - 180; // 180 margin for text
  int dimY = height / labels.length;

  for (int i = 0; i < labels.length; i++)
    channels[i] = new Channel(this, labels[i], mins[i], maxs[i], dimX, dimY, dimY * i);

  println("Available serial ports:");
  println(Serial.list());

  port = new Serial(this, Serial.list()[1], 9600);

  // clear and wait for linefeed
  port.clear();
  port.bufferUntil(LINE_FEED);
}

void draw()
{
  background(0xFFFFFFFF); // white

  // update channels
  if (currentID > lastID) {
    for (int i = 0; i < labels.length; i++) channels[i].addData(m[i] * vals[i] + n[i]);
    lastID = currentID;
  }

  // al the same
  int dimX = channels[0].dimX;

  // draw channels
  for (int i = 0; i < labels.length; i++) {
    channels[i].draw();

    int offY = channels[i].offY;

    // add lines
    stroke(0xFF000000); // black
    line(0, offY, width, offY);

    // add labels
    fill(0xFFFF0000); // red
    text(channels[i].label, dimX + 5, offY + 20);
    text(channels[i].getCurrentData(), dimX + 60, offY + 50);
    text(channels[i].minval,           dimX + 60, offY + 80);
    text(channels[i].maxval,           dimX + 60, offY + 110);
    fill(0xFF000000); // black
    text("now:", dimX + 5, offY + 50);
    text("min:", dimX + 5, offY + 80);
    text("max:", dimX + 5, offY + 110);
  }

  // draw text seperator, based on first scope
  stroke(0xFF000000); // black
  line(dimX, 0, dimX, height);
}

// handle serial data
void serialEvent(Serial p)
{
  String data = trim(p.readStringUntil(LINE_FEED));
  if (data != null) {
    String[] data_split = split(data, ',');
    for (int i = 0; i < labels.length; i++) vals[i] = float(data_split[i]);

    DateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
    java.util.Date date = new java.util.Date();
    int m = 5 * (date.getMinutes() / 5);
    int h = date.getHours();
    String currentLog = SERVER + "log.php?Date=" + dateFormat.format(date) + "&Time=" + twoDigits(h) +  ":" + twoDigits(m) + ":00&Location="+LOCATION;
    if (!currentLog.equals(lastLog)) {
      lastLog = currentLog;
      for (int i = 0; i < labels.length; i++) currentLog += "&" + labels[i] + "=" + vals[i];
      try {
        java.io.BufferedReader reader = new java.io.BufferedReader(new java.io.InputStreamReader(new java.net.URL(currentLog).openStream()));
        String line = reader.readLine();
        while (line != null) {
          System.out.println(line);
          line = reader.readLine();
        }
      } 
      catch (java.io.IOException e) {
        e.printStackTrace();
      }
    }
  }
  currentID++;
}

String twoDigits(int v)
{
  return "" + (v < 10 ? "0" : "") + v;
}

