
def process():
	f = open("temp.txt")
	y = []
	for x in f:
		x = x.rstrip()
		y.append(x)
	print y


if __name__ == '__main__':
	process()
