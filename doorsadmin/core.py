# coding=utf8
from django.db.models import Q
from doorsadmin.models import Niche, Net, SnippetsSet, XrumerBaseR, Agent, Event, EventLog
import datetime

def CronHourly():
    '''Функция вызывается по расписанию'''
    GenerateSnippets()
    #GenerateNets()
    GenerateDoorways()
    CheckAgentsActivity()
    RenewBasesR()

def CronDaily():
    '''Функция вызывается по расписанию'''
    GenerateSpamTasks()
    ClearEventLog()

def GenerateSnippets():
    '''Перегенерируем сниппеты'''
    dt = datetime.datetime.now()
    for snippetsSet in SnippetsSet.objects.filter(Q(active=True), Q(stateManaged='done')).all():
        if (snippetsSet.dateLastParsed==None) or (snippetsSet.dateLastParsed + datetime.timedelta(0, snippetsSet.interval*60*60, 0) < dt):
            snippetsSet.stateManaged = 'new'
            snippetsSet.save()

def GenerateNets():
    '''Плетем сети'''
    for net in Net.objects.filter(active=True).all():
        net.UpdateNet()

def GenerateDoorways():
    '''Генерируем дорвеи'''
    dd = datetime.date.today()
    for net in Net.objects.filter(active=True).all():
        if (net.doorsPerDay > 0) and (net.dateStart <= dd) and ((net.dateEnd==None) or (net.dateEnd >= dd)):
            net.GenerateDoorways()

def GenerateSpamTasks():
    '''Генерируем задания для спама'''
    for niche in Niche.objects.filter(active=True).all():
        niche.GenerateSpamTasksMultiple()

def CheckAgentsActivity():
    '''Проверяем активность агентов'''
    dt = datetime.datetime.now()
    for agent in Agent.objects.filter(active=True).all():
        if (agent.stateSimple == 'ok') and (agent.dateLastPing != None) and (agent.dateLastPing + datetime.timedelta(0, agent.interval*60*60, 0) < dt):
            EventLog('error', 'Agent long inactivity', agent)
            agent.stateSimple = 'error'
            agent.save()

def ClearEventLog():
    '''Удаляем старые записи из лога событий'''
    dt = datetime.datetime.now() - datetime.timedelta(30)  # старше 30 дней
    Event.objects.filter(date__lt=dt).delete()

def RenewBasesR():
    '''Регенерируем изношенные базы'''
    for xrumerBaseR in XrumerBaseR.objects.filter(Q(active=True), Q(stateManaged='done')).all():
        if xrumerBaseR.linksCount < 2:  # in thousands
            xrumerBaseR.stateManaged = 'new'
            xrumerBaseR.save()
