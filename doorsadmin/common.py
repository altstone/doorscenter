# coding=utf8
from django.template.defaultfilters import slugify
from django.db.models import Sum
import os, glob, math, random, urllib, string, codecs, datetime, re

def SelectKeywords(path, encoding='utf-8', count=10):
    '''Создание списка кеев для доров по Бабулеру.
    Для каждого дора из каждого файла в папке берется заданный процент кеев.'''
    percentsTotal = 0
    inKeysList = []
    '''Читаем файлы'''
    for file in glob.glob(os.path.join(path, r'[*.txt')):
        _, _, x = file.partition('[')
        x, _, _ = x.partition(']')
        percent = int(x)
        percentsTotal += percent
        tempkeys = []
        for line in codecs.open(file, 'r', encoding):
            tempkeys.append(line.strip())
        inKeysList.append((tempkeys, percent))
    '''Генерируем список'''
    outKeysList = []
    percentsCount = percentsTotal
    for keys, percent in inKeysList:
        keysCount = int(min(len(keys), math.floor(count * percent / percentsCount * random.randint(80, 120) / 100)))  # берем случайное количество кеев из очередного файла 
        if keysCount > 0:
            random.shuffle(keys)  # перемешиваем
            outKeysList.extend(keys[0:keysCount])  # добавляем в результирующий список
        count -= keysCount
        percentsCount -= percent
    '''Перемешиваем готовый список'''
    random.shuffle(outKeysList)
    return outKeysList

def CountKeywords(path):
    '''Считаем ключи'''
    total = 0
    '''Читаем файлы'''
    for file in glob.glob(os.path.join(path, r'[*.txt')):
        for _ in open(file, 'r'):
            total += 1
    return total

def AddDomainToControlPanel(domainName, ipAddress, useDNS, controlPanelType, controlPanelUrl, controlPanelServerId):
    '''Добавить домен в панель управления'''
    if controlPanelType == 'ispconfig':
        try:
            data = {'domainName': domainName, 'ipAddress': ipAddress, 'useDNS': useDNS, 'controlPanelUrl': controlPanelUrl, 'controlPanelServerId': controlPanelServerId}
            fd = urllib.urlopen(r'http://searchpro.name/tools/isp-add-domain.php', urllib.urlencode(data))
            reply = fd.read()
            fd.close()
            if reply == 'ok':
                return ''
            elif reply != '':
                return reply
            else:
                return 'unknown error'
        except Exception as error:
            return str(error)
    else:
        return ''

def AddSiteToPiwik(siteName):
    '''...'''
    try:
        fd = urllib.urlopen(r'http://searchpro.name/stat/?module=API&method=SitesManager.addSite&siteName=%s&urls=siteUrl.com&format=original&token_auth=6822e43c3b191ead08e76539ef23fa5f' % siteName)
        reply = fd.read()
        fd.close()
        return reply
    except Exception as error:
        return str(error)

validChars = "-%s%s" % (string.ascii_letters, string.digits)
conversion = {u'а':'a',u'б':'b',u'в':'v',u'г':'g',u'д':'d',u'е':'e',u'ё':'e',u'ж':'zh',
u'з':'z',u'и':'i',u'й':'j',u'к':'k',u'л':'l',u'м':'m',u'н':'n',u'о':'o',u'п':'p',
u'р':'r',u'с':'s',u'т':'t',u'у':'u',u'ф':'f',u'х':'h',u'ц':'c',u'ч':'ch',u'ш':'sh',
u'щ':'sch',u'ь' : '',u'ы':'y',u'ь' : '',u'э':'e',u'ю':'ju',u'я':'ja',
u'А':'a',u'Б':'b',u'В':'v',u'Г':'g',u'Д':'d',u'Е':'e',u'Ё':'e',u'Ж':'zh',u'З':'z',
u'И':'i',u'Й':'j',u'К':'k',u'Л':'l',u'М':'m',u'Н':'n',u'О':'o',u'П':'p',u'Р':'r',
u'С':'s',u'Т':'t',u'У':'u',u'Ф':'f',u'Х':'h',u'Ц':'c',u'Ч':'ch',u'Ш':'sh',u'Щ':'sch',
u'Ъ' : '',u'Ы':'y',u'Ь' : '',u'Э':'e',u'Ю':'ju',u'Я':'ja'}

def KeywordToUrl(key):
    '''Преобразование кея в разрешенные символы URL'''
    url = ''
    for c in key:
        if c in validChars:
            url += c
        elif c in conversion:
            url += conversion[c]
    return slugify(url)        

def GetFirstObject(objects):
    '''Первый ненулевой объект из списка'''
    for object in objects:
        if object:
            return object
    return None

def MakeListUnique(seq):
    '''Удаляет неуникальные элементы списка'''
    seen = set()
    seen_add = seen.add
    return [x for x in seq if x not in seen and not seen_add(x)]

def EncodeListForAgent(s):
    '''Перекодирует строку в win1251 и разделяет в список по переводам строки.
    Переводы могут быть как windows-style, так и unix-style.'''
    return codecs.encode(s.replace('\r\n', '\n'), 'cp1251').split('\n')

def DecodeListFromAgent(l):
    '''Преобразует список строк в строку с переводами строки unix-style 
    и декодирует ее из win1251 и разделяет ее на списки по переводам строки.'''
    return codecs.decode('\n'.join(l), 'cp1251')

def GenerateRandomWord(length):
    '''Генерация случайного набора букв заданной длины'''
    return ''.join(random.choice(string.letters) for _ in xrange(length))

rxHtml = re.compile(r'<a href="(.*)">(.*)</a>')
def HtmlLinksToBBCodes(l):
    '''Конвертация списка строк вида <a href="xxx">yyy</a> --> [url=xxx]yyy[/url]'''
    result = []
    for line in l:
        try:
            groups = rxHtml.match(line).groups()
            if len(groups) == 2:
                result.append('[url=%s]%s[/url]' % groups)
        except Exception:
            pass
    return result

rxBbcode = re.compile(r'\[url=(.*)\](.*)\[/url\]')
def BBCodesToHtmlLinks(l):
    '''Конвертация списка строк вида [url=xxx]yyy[/url] --> <a href="xxx">yyy</a>'''
    result = []
    for line in l:
        try:
            groups = rxBbcode.match(line).groups()
            if len(groups) == 2:
                result.append('<a href="%s">%s</a>' % groups)
        except Exception:
            pass
    return result

def PrettyDate(time=False):
    '''Get a datetime object or a int() Epoch timestamp and return a
    pretty string like 'an hour ago', 'Yesterday', '3 months ago',
    'just now', etc'''
    now = datetime.datetime.now()
    if type(time) is int:
        diff = now - datetime.datetime.fromtimestamp(time)
    elif time:
        diff = now - time
    else:
        return ''
    second_diff = diff.seconds
    day_diff = diff.days

    if day_diff < 0:
        return ''

    if day_diff == 0:
        if second_diff < 10:
            return "just now"
        if second_diff < 60:
            return str(second_diff) + " seconds ago"
        if second_diff < 120:
            return  "a minute ago"
        if second_diff < 3600:
            return str( second_diff / 60 ) + " minutes ago"
        if second_diff < 7200:
            return "an hour ago"
        if second_diff < 86400:
            return str( second_diff / 3600 ) + " hours ago"
    if day_diff == 1:
        return "yesterday"
    if day_diff < 7:
        return str(day_diff) + " days ago"
    if day_diff < 31:
        return str(day_diff / 7) + " weeks ago"
    if day_diff < 365:
        return str(day_diff / 30) + " months ago"
    return str(day_diff / 365) + " years ago"

def GetCounter(objects, filterCondition, warningCondition = None):
    '''Возвращает общее число объектов и число, удовлетворяющее заданному условию'''
    n1 = objects.filter(**filterCondition).count()
    if n1 != 0:
        s1 = '%d' % n1
    else: 
        s1 = '-'
    n2 = objects.count()
    if n2 != 0:
        s2 = '%d' % n2
    else: 
        s2 = '-'
    if warningCondition and warningCondition(n1):
        return '<font color="red">%s/%s</font>' % (s1, s2)
    else:
        return '%s/%s' % (s1, s2)

def GetPagesCounter(objects):
    '''То же самое, но для страниц доров'''
    try:
        n = '%s' % objects.aggregate(x = Sum('pagesCount'))['x']
        if n == '0' or n == 'None':
            n = '-'
        return n
    except Exception:
        return '-'

def ReplaceZero(s):
    '''Заменяем None на тире'''
    if s == None or s == 0 or s == 'None' or s == '0':
        return '-'
    else:
        return s
