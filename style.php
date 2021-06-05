<?php header("Content-type: text/css");?>
* {
    box-sizing: border-box;
}

.home {
    margin-bottom: 2.8em;
}

.home-page-img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    margin-top: 3em;
}

.home-search-bar {
    display: inline;
    height: 2.5em;
    width: 35em;
    font-size:16px;
    outline: none;
    margin-top: 3em;
    margin-right: 0.7em;
    padding-left: 1.5em;
    border-radius:8px;
    border:1px solid #dcdcdc;
  }

.home-search-submit {
    display: inline;
    height: 2.5em;
    outline: none;
    margin-top: 3em;
    padding: .6em 1.4em;
    font-family: 'IBM Plex Sans', sans-serif;
    font-size: .9em;
    color: white;
    background-color: #7084EA;
    border-radius:8px;
    border: none;
}

.home-search-submit:hover {
  background-color: #5d71d4;
  transform: scale(1.05);
  transition: all ease 0.3s;
}

.home-search-submit:active {
  transform: scale(0.95);
  transition: all ease 0.2s;
}

.results {
    display: block;
    margin-top: 1em;
    margin-left: 2em;
}
