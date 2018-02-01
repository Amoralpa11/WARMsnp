out = open("../databases/IDens_coord.txt","w")
out.write("chr" + "\t" + "ensembl_ID" + "\t" + "in_coord" + "\t" + "end_coord" + "\n")
with open("../databases/Homo_sapiens.GRCh38.91.gtf") as fh:
    for line in fh:
        if "#!" not in line:
            # print(line.split()[2])

            if "gene" in line.split()[2]:
                out.write(line.split()[0] + "\t" + line.split()[9][1:len(line.split()[9])-2] + "\t" + line.split()[3] + "\t" + line.split()[4] + "\n")
