--
-- Created by IntelliJ IDEA.
-- User: user
-- Date: 2017/2/8
-- Time: 18:10
-- To change this template use File | Settings | File Templates.
--
local key_exists = redis.call('exists', 'number_id')
if key_exists == 0 then
    local i
    for i = 1, 2000 do
        redis.call('lpush', 'number_id', i)
    end
end
local number3 = redis.call('rpop', 'number_id')
redis.call('lpush', 'number_id', number3)
local timeInfo = redis.call('time')
if tonumber(timeInfo[2]) < 100000 then
    timeInfo[2] = timeInfo[2] .. "0"
end
return tostring(timeInfo[1]) .. tostring(timeInfo[2]) .. tostring((1000 + tonumber(number3)))
