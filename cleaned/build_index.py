import pyterrier as pt

if not pt.started():
	pt.init()

files = pt.io.find_files("./www.cdc.gov")
indexer = pt.FilesIndexer("./cdc_index", meta={"docno": 20, "filename": 512, "title": 512}, meta_tags={"title": "title"})
indexref = indexer.index(files)
