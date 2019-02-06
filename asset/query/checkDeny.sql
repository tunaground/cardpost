select  deny_uid
from    deny
where   card_uid = :card_uid
    and user_id = :user_id
    and status = :status