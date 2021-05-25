import pyterrier as pt
if not pt.started():
	pt.init()

import pandas as pd

with open("./session/query", "r") as fr:
	search_string = fr.read()
with open("./session/group", "r") as fr:
	user_group = fr.read()

query = pd.DataFrame([["q1", search_string]], columns=["qid", "query"])
index = pt.IndexFactory.of("./cleaned/cdc_index/data.properties")
bm25 = pt.BatchRetrieve(index, wmodel="BM25", metadata=["docno", "filename", "title"], num_results=15)
results = bm25.transform(query)

for i in range(len(results)):
	real_url = results["filename"][i][1:]
	real_url = "https://" + real_url[real_url.index("/") + 1:]
	print("<a href={} id=\"{}\">{}</a><br>".format(real_url, results["docno"][i], results["title"][i]))

