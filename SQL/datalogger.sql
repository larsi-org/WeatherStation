CREATE TABLE `datalogger` (
  `DateTime` datetime NOT NULL,
  `Location` enum('Attic','BathRoom2','Bathroom3','Bedroom2','Bedroom2','Bench','Den','DiningRoom','FamilyRoom','Garage','Garden','GuestRoom','HisOffice','HerOffice','KidsRoom1','KidsRoom2','KidsRoom3','Kitchen','LivingRoom','ManCave','MasterBathRoom','MasterBedRoom','MediaRoom','Office','Porch','Study','UtilityRoom','WorkoutRoom') NOT NULL,
  `Type` enum('DewPoint','Energy','LightTEMT6000','Power','Pressure','RelativeHumidity','RPM','Temperature') NOT NULL,
  `Value` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
