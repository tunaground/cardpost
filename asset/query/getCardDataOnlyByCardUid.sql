select	card_uid,
		bbs_uid,
		title,
		password,
		open_date,
		refresh_date,
		owner_only,
		dead,
		status
from    card
where   card_uid = :card_uid