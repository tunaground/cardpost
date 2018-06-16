select	post_uid,
		card_uid,
		bbs_uid,
		post_order,
		name,
		user_id,
		create_date,
		content,
		image,
		ip,
		status
from	post
where	1 = 1
	and	card_uid = :card_uid
order
	by	post_uid asc
