#!/usr/bin/python
# -*- coding: utf-8 -*-
# Connect to Oregon Scientific BLE Weather Station
# Copyright (c) 2016 Arnaud Balmelle
#
# This script will connect to Oregon Scientific BLE Weather Station
# and retrieve the temperature of the base and sensors attached to it.
# If no mac-address is passed as argument, it will scan for an Oregon Scientific BLE Weather Station.
#
# Supported Oregon Scientific Weather Station: EMR211 and RAR218HG (and probably BAR218HG)
#
# Usage: python bleWeatherStation.py [mac-address]
#
# Dependencies:
# - Bluetooth 4.1 and bluez installed
# - bluepy library (https://github.com/IanHarvey/bluepy)
#
# License: Released under an MIT license: http://opensource.org/licenses/MIT

import sys
import logging
import time
import sqlite3
import pprint
from bluepy.btle import *

# uncomment the following line to get debug information
# logging.basicConfig(format='%(asctime)s: %(message)s', level=logging.DEBUG)
logging.basicConfig(format='%(message)s', level=logging.DEBUG)

WEATHERSTATION_NAME = "IDTW213R" # IDTW213R for RAR218HG

class WeatherStation:
    def __init__(self, mac):
        self._data = {}
        try:
            self.p = Peripheral(mac, ADDR_TYPE_RANDOM)
            self.p.setDelegate(NotificationDelegate())
            # logging.debug('WeatherStation connected !')
        except BTLEException:
            self.p = 0
            logging.debug('Connection to WeatherStation failed !')
            raise

    def _enableNotification(self):
        try:
            # Enable all notification or indication
            self.p.writeCharacteristic(0x000c, "\x02\x00")
            self.p.writeCharacteristic(0x000f, "\x02\x00")
            self.p.writeCharacteristic(0x0012, "\x02\x00")
            self.p.writeCharacteristic(0x0015, "\x01\x00")
            self.p.writeCharacteristic(0x0018, "\x02\x00")
            self.p.writeCharacteristic(0x001b, "\x02\x00")
            self.p.writeCharacteristic(0x001e, "\x02\x00")
            self.p.writeCharacteristic(0x0021, "\x02\x00")
            self.p.writeCharacteristic(0x0032, "\x01\x00")
            # logging.debug('Notifications enabled')

        except BTLEException as err:
            print(err)
            self.p.disconnect()

    def monitorWeatherStation(self):
        try:
            # Enable notification
            self._enableNotification()
            # Wait for notifications
            while self.p.waitForNotifications(1.0):
                # handleNotification() was called
                continue
        except:
            return None

        regs = self.p.delegate.getData()
        pprint.pprint(regs)
        if regs is not None:
            # indoor and channel 1 to 3 data
            self._data['index0_temperature'] = ''.join(regs['data_ch0_3'][4:6] + regs['data_ch0_3'][2:4])
            self._data['index1_temperature'] = ''.join(regs['data_ch0_3'][8:10] + regs['data_ch0_3'][6:8])
            self._data['index2_temperature'] = ''.join(regs['data_ch0_3'][12:14] + regs['data_ch0_3'][10:12])
            self._data['index3_temperature'] = ''.join(regs['data_ch0_3'][16:18] + regs['data_ch0_3'][14:16])
            self._data['index0_humidity'] = regs['data_ch0_3'][18:20]
            self._data['index1_humidity'] = regs['data_ch0_3'][20:22]
            self._data['index2_humidity'] = regs['data_ch0_3'][22:24]
            self._data['index3_humidity'] = regs['data_ch0_3'][24:26]

            # channel 4 to 7 data
            self._data['index4_temperature'] = ''.join(regs['ch_4_7'][4:6] + regs['ch_4_7'][2:4])
            self._data['index5_temperature'] = ''.join(regs['ch_4_7'][8:10] + regs['ch_4_7'][6:8])
            self._data['index6_temperature'] = ''.join(regs['ch_4_7'][12:14] + regs['ch_4_7'][10:12])
            self._data['index7_temperature'] = ''.join(regs['ch_4_7'][16:18] + regs['ch_4_7'][14:16])
            self._data['index4_humidity'] = regs['ch_4_7'][18:20]
            self._data['index5_humidity'] = regs['ch_4_7'][20:22]
            self._data['index6_humidity'] = regs['ch_4_7'][22:24]
            self._data['index7_humidity'] = regs['ch_4_7'][24:26]

            if regs['data_pressure'] is not None:
                self._data['index0_pressure'] = ''.join(regs['data_pressure'][12:14] + regs['data_pressure'][10:12])
            else:
                self._data['index0_pressure'] = '';
            return True
        else:
            return None

    def getValue(self, indexstr):
        val = int(self._data[indexstr], 16)
        if val >= 0x8000:
            val = ((val + 0x8000) & 0xFFFF) - 0x8000
        return val

    def getData(self):
        if 'index0_temperature' in self._data:
            temp0 = self.getValue('index0_temperature') / 10.0
            hum0 = self.getValue('index0_humidity')
            pre0 = self.getValue('index0_pressure')
            logging.debug('%d , %.1f , %.1f , %.1f', 0, temp0, hum0, pre0)

        if 'index1_temperature' in self._data:
            temp1 = self.getValue('index1_temperature') / 10.0
            hum1 = self.getValue('index1_humidity')
            logging.debug('%d , %.1f , %.1f', 1, temp1, hum1)

        if 'index2_temperature' in self._data:
            temp2 = self.getValue('index2_temperature') / 10.0
            hum2 = self.getValue('index2_humidity')
            logging.debug('%d , %.1f , %.1f', 2, temp2, hum2)

        if 'index3_temperature' in self._data:
            temp3 = self.getValue('index3_temperature') / 10.0
            hum3 = self.getValue('index3_humidity')
            logging.debug('%d , %.1f , %.1f', 3, temp3, hum3)

        if 'index4_temperature' in self._data:
            temp4 = self.getValue('index4_temperature') / 10.0
            hum4 = self.getValue('index4_humidity')
            logging.debug('%d , %.1f , %.1f', 4, temp4, hum4)

        if 'index5_temperature' in self._data:
            temp5 = self.getValue('index5_temperature') / 10.0
            hum5 = self.getValue('index5_humidity')
            logging.debug('%d , %.1f , %.1f', 5, temp5, hum5)

        if 'index6_temperature' in self._data:
            temp6 = self.getValue('index6_temperature') / 10.0
            hum6 = self.getValue('index6_humidity')
            logging.debug('%d , %.1f , %.1f', 6, temp6, hum6)

        if 'index7_temperature' in self._data:
            temp7 = self.getValue('index7_temperature') / 10.0
            hum7 = self.getValue('index7_humidity')
            logging.debug('%d , %.1f , %.1f', 7, temp7, hum7)

    def disconnect(self):
        self.p.disconnect()

class NotificationDelegate(DefaultDelegate):
    def __init__(self):
        DefaultDelegate.__init__(self)
        self._indoorAndOutdoorTemp_ch0_3 = None
        self._indoorAndOutdoorTemp_type2 = None
        self._pressure = None

    def handleNotification(self, cHandle, data):
        formatedData = binascii.b2a_hex(data)
        if cHandle == 0x0017:
            # indoorAndOutdoorTemp indication received ch 0-3
            if formatedData[0] != '8':
                # ch0_3 data packet received
                self._indoorAndOutdoorTemp_ch0_3 = formatedData
        if cHandle == 0x0020:
            # indoorAndOutdoorTemp indication received ch 4-7
            if formatedData[0] != '8':
                # Type2 data packet received
                self._indoorAndOutdoorTemp_type2 = formatedData

        if cHandle == 0x001a:
            # indoorAndOutdoorTemp indication received ch 4-7
            if formatedData[0] != '8':
                # Type2 data packet received
                self._pressure = formatedData

    def getData(self):
            if self._indoorAndOutdoorTemp_ch0_3 is not None:
                # return sensors data
                return {'data_ch0_3':self._indoorAndOutdoorTemp_ch0_3, 'ch_4_7':self._indoorAndOutdoorTemp_type2, 'data_pressure':self._pressure}
            else:
                return None

class ScanDelegate(DefaultDelegate):
    def __init__(self):
        DefaultDelegate.__init__(self)

    def handleDiscovery(self, dev, isNewDev, isNewData):
        global weatherStationMacAddr
        if dev.getValueText(9) == WEATHERSTATION_NAME:
            # Weather Station in range, saving Mac address for future connection
            logging.debug('WeatherStation found')
            weatherStationMacAddr = dev.addr

if __name__=="__main__":

    weatherStationMacAddr = None

    if len(sys.argv) < 2:
        # No MAC address passed as argument
        try:
            # Scanning to see if Weather Station in range
            scanner = Scanner().withDelegate(ScanDelegate())
            devices = scanner.scan(2.0)
        except BTLEException as err:
            print(err)
            print('Scanning required root privilege, so do not forget to run the script with sudo.')
    else:
        # Weather Station MAC address passed as argument, will attempt to connect with this address
        weatherStationMacAddr = sys.argv[1]

    if weatherStationMacAddr is None:
        logging.debug('No WeatherStation in range !')
    else:
        try:
            # Attempting to connect to device with MAC address "weatherStationMacAddr"
            weatherStation = WeatherStation(weatherStationMacAddr)

            if weatherStation.monitorWeatherStation() is not None:
                # WeatherStation data received
                indoor = weatherStation.getData()
            else:
                logging.debug('No data received from WeatherStation')

            weatherStation.disconnect()

        except KeyboardInterrupt:
            logging.debug('Program stopped by user')
