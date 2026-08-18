
SELECT table_name FROM information_schema.columns WHERE column_name = 'exam_centre_id' AND table_schema = 'eprap_portal';


exam_schedules
practice_schedules
students



SELECT exam_centre_id, COUNT(id) FROM `exam_schedules` GROUP BY `exam_centre_id`; -----------  8
SELECT exam_centre_id, COUNT(id) FROM `practice_schedules` GROUP BY `exam_centre_id`; -------  8 
SELECT exam_centre_id, COUNT(id) FROM `students` GROUP BY `exam_centre_id`; ----------------- 39


SELECT id,exam_id,name, 
(SELECT COUNT(id) FROM `exam_schedules` WHERE exam_centre_id = exam_centres.id ) as esc_qty, 
(SELECT COUNT(id) FROM `practice_schedules` WHERE exam_centre_id = exam_centres.id ) as ps_qty, 
(SELECT COUNT(id) FROM `students` WHERE exam_centre_id = exam_centres.id ) as stu_qty 
FROM `exam_centres` WHERE name = 'Manchester';



UPDATE `students` SET `exam_centre_id` = 1 WHERE `exam_centre_id` IN ( 46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,68 );
DELETE FROM `exam_centres` WHERE id in( 46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,68	);



SELECT id,fname,email,exam_centre_id FROM `students` WHERE `exam_centre_id` IN ( 46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,68 );



/*
id	exam_id	name	esc_qty	ps_qty	stu_qty	
1	1	Manchester	1092	1544	15160	
2	1	Lagos	2	4	43	
3	1	Dubai	22	0	15	
10	4	Essex	2	0	2	
12	5	London Plab 1	1	2	11	
13	6	London Plab 3	2	3	4	
16	6	Harare	0	0	55	
40	1	Bangalore	0	4	1	
42	1	Cairo	0	21	8	
43	1	Lahore	0	4	0	
44	1	London	81	29	127	
45	1	Khatourm	0	0	1	
46	1	manchester	0	0	119	
47	1	Manchester	0	0	20	
48	1	Manchester 	0	0	19	
49	1	Manchester	0	0	131	
50	1	manchester	0	0	239	
51	1	Manchester	0	0	3	
52	1	Manchester	0	0	1	
53	1	Manchester	0	0	25	
54	1	Manchester	0	0	55	
55	1	Manchester 	0	0	14	
56	1	Manchester	0	0	2	
57	1	Manchester	0	0	58	
58	1	Manchester	0	0	6	
59	1	Manchester 	0	0	24	
60	1	Manchester	0	0	14	
61	1	manchester	0	0	6	
62	1	Manchester 	0	0	3	
63	1	Manchester 	0	0	7	
64	1	Manchester 	0	0	11	
65	1	M	0	0	1	
66	1	Lahore	0	0	1	
67	6	Durban	0	0	1	
68	1	Manchester 	0	0	1	
69	1	Jos 	0	0	1	
70	1	General medical Council	0	0	1	
71	8	London	1	0	0	
*/
