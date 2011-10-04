/*
 * Channel.java by Lars Schumann (make.larsi.org)
 *
 * Draws a float array as a graph
 *
 * It is based on Channel.java by David Konsumer <david.konsumer@gmail.com>
 * but had to be modified
 */

import processing.core.PApplet;
import processing.core.PConstants;

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
    return parent.round(parent.map(values[index], minval, maxval, offY + dimY - 2, offY + 1));
  }
}
