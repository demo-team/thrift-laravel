namespace php Demo

struct AccountInfo {
  1: i32 userId = 0,
  2: string name = '',
  3: string email = '',
}

exception InvalideOperation {
   1: i32 code,
   2: string message
}

service Account
{
    i32 setUserInfo(1:AccountInfo accountInfo) throws (1:InvalideOperation ouch),
    AccountInfo getUserInfoByEmail(1:string email) throws (1:InvalideOperation ouch)
}
