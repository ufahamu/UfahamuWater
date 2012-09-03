/*
autopilot 0.0.1
[{"id":"Map","background-color":"#b8dee6"},{"id":"countries","polygon-fill":["#fff"]},{"id":"10mlakes","_line-delta":1.1,"line-width":1,"polygon-fill":["#d1d1d1"],"line-color":"#ffffff"},{"id":"typhoid","_marker-delta":1.2,"marker-width":[2,10],"marker-fill":["#48c6c4"],"marker-line-width":[0,10]},{"id":"typhoidmap","_marker-delta":1.1,"marker-width":[3,10],"marker-fill":["#2fad9d"],"marker-line-width":[0,10]},{"id":"countycenterpopulati","_marker-delta":1.2,"marker-fill":["#2d7faa"]}]

This stylesheet is managed by autopilot. Any changes will
be overwritten unless you disable autopilot for this project.
*/

Map { background-color: #b8dee6; }

#countries { ::polygon { polygon-fill: #fff; } }

#10mlakes {
  ::polygon { polygon-fill: #d1d1d1; }
  ::line {
    [zoom=0] { line-width: 1*1.00; }
    [zoom=1] { line-width: 1*1.10; }
    [zoom=2] { line-width: 1*1.21; }
    [zoom=3] { line-width: 1*1.33; }
    [zoom=4] { line-width: 1*1.46; }
    [zoom=5] { line-width: 1*1.61; }
    [zoom=6] { line-width: 1*1.77; }
    [zoom=7] { line-width: 1*1.95; }
    [zoom=8] { line-width: 1*2.14; }
    [zoom=9] { line-width: 1*2.36; }
    [zoom=10] { line-width: 1*2.59; }
    [zoom=11] { line-width: 1*2.85; }
    [zoom=12] { line-width: 1*3.14; }
    [zoom=13] { line-width: 1*3.45; }
    [zoom=14] { line-width: 1*3.80; }
    [zoom=15] { line-width: 1*4.18; }
    [zoom=16] { line-width: 1*4.59; }
    [zoom=17] { line-width: 1*5.05; }
    [zoom=18] { line-width: 1*5.56; }
    [zoom=19] { line-width: 1*6.12; }
    [zoom=20] { line-width: 1*6.73; }
    [zoom=21] { line-width: 1*7.40; }
    [zoom=22] { line-width: 1*8.14; }
    line-color: #ffffff;
  }
}



