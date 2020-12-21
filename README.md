# secret-santa-codechallenge-510

An attempt at providing a solution for https://github.com/Intracto/SecretSanta/issues/510

# Challenge requirements
##### Write an algorithm that assigns every participant to another participant

- Given participants A, B, C, D, E.
- Could result in A -> B -> C -> D -> E -> A (randomization is required)
- Multiple loops like A -> B -> A + C -> D -> E -> C are allowed
- A -> A is not allowed

##### Must work with an odd number of participants

##### Participants can have (multiple) excludes 

Eg this must work:
- A: exclude C, D, E
- B: exclude A, D, E
- C: exclude A, B, E
- D: exclude A, B, C
- E: exclude B, C, D

In this case the only valid list is **A -> B -> C -> D -> E -> A**

##### The solution must error-handle a situation that can't be resolved. 
Eg with participants A, B, C:
- A: exclude C
- B: exclude C
- C: -

This would result in **A -> B -> A** and a **C** that can't be assigned.

##### Must run in an acceptable time with > 200 participants.

# Project setup
Make sure you have PHP 7.4 or higher installed. 

# Execute script

In the terminal, navigate to project root and execute command 
```
php secretsanta.php
```

Script defaults:
```
$numberOfParticipants = 250;
$numberOfExcludes = 4;
```

## Sample output

```
secret-santa-challenge jorim.vanhove$ php secretsanta.php
Matched participants:
0 -> 196
1 -> 139
2 -> 136
3 -> 0
4 -> 5
5 -> 225
6 -> 56
7 -> 213
8 -> 226
9 -> 117
10 -> 35
11 -> 72
12 -> 173
13 -> 138
14 -> 209
15 -> 221
16 -> 198
17 -> 6
18 -> 124
19 -> 174
20 -> 242
21 -> 104
22 -> 140
23 -> 203
24 -> 111
25 -> 105
26 -> 148
27 -> 60
28 -> 178
29 -> 146
30 -> 62
31 -> 24
32 -> 158
33 -> 113
34 -> 219
35 -> 121
36 -> 214
37 -> 197
38 -> 141
39 -> 4
40 -> 151
41 -> 204
42 -> 67
43 -> 207
44 -> 110
45 -> 36
46 -> 126
47 -> 157
48 -> 217
49 -> 165
50 -> 61
51 -> 93
52 -> 16
53 -> 43
54 -> 107
55 -> 120
56 -> 84
57 -> 169
58 -> 116
59 -> 175
60 -> 145
61 -> 167
62 -> 41
63 -> 64
64 -> 194
65 -> 193
66 -> 63
67 -> 118
68 -> 38
69 -> 86
70 -> 182
71 -> 75
72 -> 191
73 -> 176
74 -> 177
75 -> 70
76 -> 108
77 -> 227
78 -> 154
79 -> 163
80 -> 47
81 -> 161
82 -> 96
83 -> 102
84 -> 66
85 -> 150
86 -> 231
87 -> 201
88 -> 248
89 -> 48
90 -> 202
91 -> 23
92 -> 90
93 -> 71
94 -> 125
95 -> 69
96 -> 149
97 -> 186
98 -> 54
99 -> 81
100 -> 49
101 -> 190
102 -> 222
103 -> 19
104 -> 100
105 -> 224
106 -> 65
107 -> 230
108 -> 155
109 -> 119
110 -> 147
111 -> 115
112 -> 20
113 -> 205
114 -> 153
115 -> 195
116 -> 184
117 -> 7
118 -> 241
119 -> 144
120 -> 77
121 -> 237
122 -> 229
123 -> 80
124 -> 51
125 -> 25
126 -> 244
127 -> 236
128 -> 123
129 -> 143
130 -> 3
131 -> 132
132 -> 46
133 -> 134
134 -> 9
135 -> 59
136 -> 109
137 -> 171
138 -> 160
139 -> 168
140 -> 98
141 -> 183
142 -> 73
143 -> 218
144 -> 233
145 -> 87
146 -> 74
147 -> 220
148 -> 250
149 -> 45
150 -> 79
151 -> 159
152 -> 31
153 -> 2
154 -> 28
155 -> 114
156 -> 78
157 -> 82
158 -> 133
159 -> 208
160 -> 13
161 -> 199
162 -> 37
163 -> 106
164 -> 103
165 -> 1
166 -> 243
167 -> 40
168 -> 210
169 -> 181
170 -> 15
171 -> 249
172 -> 55
173 -> 187
174 -> 142
175 -> 95
176 -> 83
177 -> 101
178 -> 152
179 -> 185
180 -> 10
181 -> 52
182 -> 17
183 -> 29
184 -> 26
185 -> 21
186 -> 44
187 -> 97
188 -> 170
189 -> 85
190 -> 127
191 -> 122
192 -> 33
193 -> 129
194 -> 212
195 -> 88
196 -> 99
197 -> 50
198 -> 156
199 -> 200
200 -> 234
201 -> 131
202 -> 11
203 -> 245
204 -> 216
205 -> 30
206 -> 42
207 -> 223
208 -> 22
209 -> 58
210 -> 18
211 -> 172
212 -> 32
213 -> 57
214 -> 14
215 -> 211
216 -> 166
217 -> 232
218 -> 94
219 -> 247
220 -> 34
221 -> 53
222 -> 12
223 -> 76
224 -> 188
225 -> 135
226 -> 239
227 -> 240
228 -> 27
229 -> 112
230 -> 235
231 -> 238
232 -> 192
233 -> 180
234 -> 246
235 -> 8
236 -> 189
237 -> 89
238 -> 179
239 -> 39
240 -> 228
241 -> 206
242 -> 164
243 -> 162
244 -> 68
245 -> 128
246 -> 130
247 -> 137
248 -> 92
249 -> 215
250 -> 91
This process used 3 ms for its computations
It spent 2 ms in system calls

```
# Unit tests
- Execute `composer install` before running the tests. 
- To run the tests execute `vendor/bin/phpunit tests`

## Output
```
secret-santa-challenge jorim.vanhove$ vendor/bin/phpunit tests
PHPUnit 9.5.0 by Sebastian Bergmann and contributors.

...                                                                 3 / 3 (100%)

Time: 00:00.012, Memory: 6.00 MB

OK (3 tests, 4 assertions)

```


