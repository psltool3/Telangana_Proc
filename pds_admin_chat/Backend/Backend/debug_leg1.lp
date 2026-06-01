\* Supply-Demand-Problem *\
Minimize
OBJ: 203.161533751 X_1212121_Adilabad_1000_Adilabad_Rice
 + 203.161533751 X_1212121_Adilabad_7890_Adilabad_Rice
 + 203.161533751 X_34534_Adilabad_1000_Adilabad_Rice
 + 203.161533751 X_34534_Adilabad_7890_Adilabad_Rice
Subject To
_C1: X_1212121_Adilabad_1000_Adilabad_Rice
 + X_34534_Adilabad_1000_Adilabad_Rice >= 230
_C2: X_1212121_Adilabad_7890_Adilabad_Rice
 + X_34534_Adilabad_7890_Adilabad_Rice >= 230
_C3: X_1212121_Adilabad_1000_Adilabad_Rice
 + X_1212121_Adilabad_7890_Adilabad_Rice <= 300
_C4: X_34534_Adilabad_1000_Adilabad_Rice + X_34534_Adilabad_7890_Adilabad_Rice
 <= 400
Bounds
 0 <= X_1212121_Adilabad_1000_Adilabad_Rice
 0 <= X_1212121_Adilabad_7890_Adilabad_Rice
 0 <= X_34534_Adilabad_1000_Adilabad_Rice
 0 <= X_34534_Adilabad_7890_Adilabad_Rice
End
