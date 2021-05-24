import pandas as pd
import pyterrier as pt
if not pt.started():
	pt.init()

index = pt.IndexFactory.of("./cdc_index/data.properties")
bm25 = pt.BatchRetrieve(index, wmodel="BM25", metadata=["docno", "filename", "title"], num_results=15)
results = bm25.transform(pd.DataFrame([["q1", "should I wear a mask"]], columns=["qid", "query"]))
for filename in results['filename']:
	print(filename)

