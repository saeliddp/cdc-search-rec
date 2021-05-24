from bs4 import BeautifulSoup
import os
whitelist = ['p', 'h1', 'h2', 'h3', 'h4', 'li']

def clean(filepath):
	with open(filepath, 'r') as fr:
		soup = BeautifulSoup(fr, 'html.parser')
		text = soup.find_all(text=True)
		raw_text = ''
		for t in text:
			if t.parent.name in whitelist:
				raw_text += '{} '.format(t)
	title = soup.find_all('title')
	if len(title) > 0:
		raw_title = title[0].get_text()
		with open(filepath, 'w') as fw:
			fw.write('<html>\n<body>\n<title>{}</title>\n<p>\n{}\n</p>\n</body>\n</html>'.format(raw_title, raw_text))
	else:
		os.remove(filepath)

root_dir = './www.cdc.gov'

for dir_name, subdir_list, file_list in os.walk(root_dir):
	for fname in file_list:
		clean(dir_name + '/' + fname)
		
