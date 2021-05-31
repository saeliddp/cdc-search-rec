lines = []
labels = 'group'
for i in range(923):
	labels += ',d' + str(i + 1)
lines.append(labels)

poss_answers = ['x', 'y', 'n']
for one in poss_answers:
	for two in poss_answers:
		for three in poss_answers:
			entries = '\n' + one + two + three
			for i in range(923):
				entries += ',0'
			lines.append(entries)
with open('matrix.csv', 'w') as fw:
	fw.writelines(lines)

