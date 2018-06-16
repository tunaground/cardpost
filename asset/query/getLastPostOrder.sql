select	max(post_order)
from	post
where	1 = 1
	and	card_uid = :card_uid
