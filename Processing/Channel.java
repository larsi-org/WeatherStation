/*
  This is an Channel library for Processing
 
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

import processing.core.PApplet;
import processing.core.PConstants;

/**
 * this is a template class and can be used to start a new processing library.
 * make sure you rename this class as well as the name of the package template
 * this class belongs to.
 * 
 * @example ArduinoScope
 * @author David Konsumer
 * 
 */
public class Channel implements PConstants
{
  PApplet parent;
  String label;
  float minval;
  float maxval;
  int dimX; // width
  int dimY; // height
  int offY; // y start position
  int COLOR_GRAPH; // color for lines
  int COLOR_CENTER; // color for center line

  private float[] values; // all values in the graph

  /**
   * a Constructor, usually called in the setup() method in your sketch to initialize and start the library.
   * 
   * @example Channel
   * @param theParent
   */
  public Channel(PApplet parent, String label, float minval, float maxval, int dimX, int dimY, int offY)
  {
    this.parent = parent;
    this.label = label;
    this.minval = minval;
    this.maxval = maxval;
    this.dimX = dimX;
    this.dimY = dimY;
    this.offY = offY;

    // set some defaults
    COLOR_GRAPH = 0xFFFF0000; // red
    COLOR_CENTER = 0xFF999999; // gray

    values = new float[dimX];
    for (int i = 0; i < dimX; i++) values[i] = minval;    
  }

  public void draw()
  {
    // draw center line
    parent.stroke(COLOR_CENTER);
    parent.line(0, offY + (dimY/2), dimX, offY + (dimY/2));

    parent.stroke(COLOR_GRAPH);
    int yOld = getY(0);
    for (int x = 1; x < dimX; x++) {
      int yNew = getY(x);
      parent.line(x, yOld, x, yNew);
      yOld = yNew;
    }
  }

  // add a single point
  public void addData(float val)
  {
    for (int i = 0; i < dimX - 1; i++) values[i] = values[i + 1];
    values[dimX - 1] = val;
    if (val < minval) minval = val;
    if (val > maxval) maxval = val;
  }

  // add a single point
  public float getCurrentData()
  {
    return values[dimX - 1];
  }

  private int getY(int index)
  {
    return parent.round(parent.map(values[index], minval, maxval, offY + dimY - 1, offY));
  }
}

