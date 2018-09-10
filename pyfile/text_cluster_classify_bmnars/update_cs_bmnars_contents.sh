#
#/home/bmnars/pyfile/text_cluster_classify_bmnars/update_cs_bmnars_contents.sh
echo "----------------------------------------"
nohup /home/bmnars/spider_porject/spider_venv/bin/python3 /home/bmnars/pyfile/text_cluster_classify_bmnars/text_cluster_classify_bmnars.py &
echo "----------"
cur_time=`date +"%Y-%m-%d %H:%M:%S"`
echo "Current Time:" $cur_time
