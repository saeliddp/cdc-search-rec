import pandas as pd
import pyterrier as pt
from sklearn.metrics.pairwise import cosine_similarity

# how much we want relevance to impact final rank [0, 1]
R_WEIGHT = 0.03

if not pt.started():
	pt.init()

# read query and group information
with open("./session/query", "r") as fr:
	search_string = fr.read()

with open("./session/group", "r") as fr:
	user_group = fr.read()

# load group-document matrix
matrix = pd.read_csv("matrix.csv", index_col="group")

# grab the row corresponding to the current group's clicks
group_dat = matrix.loc[user_group]
# calculate the cosine similarity between this row and all other rows in our matrix
similarities = cosine_similarity(matrix, group_dat.to_numpy().reshape(1, -1))

def score(docno, norm_bm25):
	# get column of click counts for the document
	doc_clicks = matrix[docno]
	# apply 1 - 1/(clicks + 1) to compress all click numbers into [0, 1]
	compressed_clicks = [1 - 1 / (clicks + 1) for clicks in doc_clicks.to_numpy()]
	# multiply each compressed click metric for a given group times the similarity
	# between that group and the current user group and sum these together
	click_score = (compressed_clicks @ similarities)[0]
	# divide by number of groups to get final click_score
	click_score = click_score / len(compressed_clicks)
	# apply the weights and return final score
	return norm_bm25 * R_WEIGHT + click_score * (1 - R_WEIGHT)

# retrieve our results
query = pd.DataFrame([["q1", search_string]], columns=["qid", "query"])
index = pt.IndexFactory.of("./cleaned/cdc_index/data.properties")
bm25 = pt.BatchRetrieve(index, wmodel="BM25", metadata=["docno", "filename", "title"], num_results=15)
results = bm25.transform(query)

# results tuples will hold relevant doc info and be used in reranking
results_tuples = []
res_len = len(results)

# rerank based on new scores 
if res_len > 0:
	# we can use these to normalize bm25 scores
	max_score = results["score"][0]
	min_score = results["score"][res_len - 1]
	for i in range(len(results)):
		docno = results["docno"][i]
		# create a real url from our filepath
		real_url = results["filename"][i][1:]
		real_url = "https://" + real_url[real_url.index("/") + 1:]

		norm_score = (results["score"][i] - min_score) / (max_score - min_score) # min score -> 0, max score -> 1
		fin_score = score(docno, norm_score)
		# docno, score, title, url
		results_tuples.append((results["docno"][i], fin_score, results["title"][i], real_url));
	
	# reorder the list based on decreasing score
	results_tuples.sort(reverse=True, key=lambda x: x[1])

for t in results_tuples:	
	print("<div class=\"results\">")
	print("<ul class=\"results-ul\">")
	print("<li><a href=\"redirect.php?real_url={}&docno={}\">{}</a></li><br/>".format(t[3], t[0], t[2]))
	print("</ul>")
	print("</div>")
