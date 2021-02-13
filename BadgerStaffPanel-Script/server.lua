RegisterNetEvent('BadgerStaff:NotePlayer')
RegisterNetEvent('BadgerStaff:WarnPlayer')
RegisterNetEvent('BadgerStaff:KickPlayer')
RegisterNetEvent('BadgerStaff:TempbanPlayer')
RegisterNetEvent('BadgerStaff:BanPlayer')

prefix = '^7[^1BadgerStaffPanel^7]^3 '
discordWebHook = ''

RegisterCommand('note', function(source, args, rawCommand)
    if IsPlayerAceAllowed(source, 'BadgerStaffPanel.Commands.Note') then 
        if #args < 2 then 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /note <id> <note>")
            return;
        end 
        if GetPlayerIdentifiers(tonumber(args[1]))[1] == nil then 
            -- Invalid syntax 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /note <id> <note>")
            return;
        end
        local id = args[1]
        local reason = table.concat(args, ' ')
        reason = reason:gsub(id, "")
        id = tonumber(id)
        TriggerClientEvent('chatMessage', source, prefix .. " " .. "Player ^5" .. GetPlayerName(id) .. " ^3has been noted with: ^2" .. reason)
        TriggerEvent('BadgerStaff:NotePlayer', source, id, reason)
    end
end)

RegisterCommand('warn', function(source, args, rawCommand)
    if IsPlayerAceAllowed(source, 'BadgerStaffPanel.Commands.Warn') then
        if #args < 2 then 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /warn <id> <reason>")
            return;
        end 
        if GetPlayerIdentifiers(tonumber(args[1]))[1] == nil then 
            -- Invalid syntax 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /warn <id> <reason>")
            return;
        end
        local id = args[1]
        local reason = table.concat(args, ' ')
        reason = reason:gsub(id, "")
        id = tonumber(id)
        TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been warned by ^1"
            .. GetPlayerName(source) .. " ^3for ^1" .. reason)
        TriggerEvent('BadgerStaff:WarnPlayer', source, id, reason)
    end
end)

RegisterCommand('kick', function(source, args, rawCommand)
    if IsPlayerAceAllowed(source, 'BadgerStaffPanel.Commands.Kick') then
        if #args < 2 then 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /kick <id> <reason>")
            return;
        end 
        if GetPlayerIdentifiers(tonumber(args[1]))[1] == nil then 
            -- Invalid syntax 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /kick <id> <reason>")
            return;
        end
        local id = args[1]
        local reason = table.concat(args, ' ')
        reason = reason:gsub(id, "")
        id = tonumber(id)
        TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been kicked by ^1"
            .. GetPlayerName(source) .. " ^3for ^1" .. reason)
        TriggerEvent('BadgerStaff:KickPlayer', source, id, reason)
    end
end)
function isNumber(str)
    local num = tonumber(str)
    if num >= 0 and num ~= nil then 
        return true;
    end
    return false;
end

RegisterCommand('tempban', function(source, args, rawCommand)
    -- /tempban <id> <time> <reason>
    -- Times: h, d, w, m 
    -- hour, day, week, month
    if IsPlayerAceAllowed(source, 'BadgerStaffPanel.Commands.Tempban') then
        if #args < 3 then 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /tempban <id> <time> <reason>")
            return;
        end 
        if GetPlayerIdentifiers(tonumber(args[1]))[1] == nil then 
            -- Invalid syntax 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /tempban <id> <time> <reason>")
            return;
        end
        local id = tonumber(args[1])
        local timeNum = args[2]:gsub("m", ""):gsub("d", ""):gsub("h", ""):gsub("s", "")
        local timeType = tostring(args[2]:sub(-1))
        local now = os.time()
        local startDate = os.date("%c", now)
        local endDate = now 
        local reason = table.concat(args, ' '):gsub(args[1], ""):gsub(args[2], "")
        local timeStr = ""
        if timeType == 'm' then 
            -- It's months 
            endDate = endDate + (60*60*24*30*timeNum)
            timeStr = "month"
        elseif timeType == 'd' then 
            -- It's days 
            endDate = endDate + (60*60*24*timeNum)
            timeStr = "day"
        elseif timeType == 'h' then 
            -- It's hours 
            endDate = endDate + (60*60*timeNum)
            timeStr = "hour"
        elseif timeType == "s" then 
            endDate = endDate + timeNum
            timeStr = "second"
        else 
            -- Error, invalid syntax 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /tempban <id> <time> <reason>\n"
                .. "Example: /tempban 1 1d This is the reason\nTypes: m = months, d = days, h = hours, s = seconds" )
            return; 
        end
        TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been temporarily banned by ^1"
            .. GetPlayerName(source) .. " ^3for ^1" .. reason)
        TriggerEvent('BadgerStaff:TempbanPlayer', source, id, endDate, reason, timeNum, timeStr)
    end
end)

RegisterCommand('ban', function(source, args, rawCommand)
    -- /ban <id> <reason>
    if IsPlayerAceAllowed(source, 'BadgerStaffPanel.Commands.Ban') then
        if #args < 2 then 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /ban <id> <reason>")
            return;
        end 
        if GetPlayerIdentifiers(tonumber(args[1]))[1] == nil then 
            -- Invalid syntax 
            TriggerClientEvent('chatMessage', source, prefix .. "^1Invalid syntax... Proper Usage: /ban <id> <reason>")
            return;
        end
        local id = args[1]
        local reason = table.concat(args, ' ')
        reason = reason:gsub(id, "")
        TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been banned by ^1"
            .. GetPlayerName(source) .. " ^3for ^1" .. reason)
        TriggerEvent('BadgerStaff:BanPlayer', source, id, reason)
    end
end)

RegisterCommand('chain', function(source, args, rawCommand)
    -- This will do punishments depending on their past punishments earned
    -- >= 3 warns ---> Kick 
    -- >= 3 kicks ---> Tempban 4h 
    -- == 1 Tempban ---> Tempban 1d 
    -- == 2 Tempban ---> Tempban 7d 
    -- == 3 Tempban ---> Permanent Ban
    -- /chain <id> <reason>
    if IsPlayerAceAllowed(source, "BadgerStaffPanel.Commands.Chain") then 
        if #args > 1 then 
            if GetPlayerIdentifiers(tonumber(args[1]))[1] ~= nil then 
                local id = tonumber(args[1]) 
                local reason = table.concat(args, ' ')
                reason = reason:gsub(id, "")
                local ids = ExtractIdentifiers(id)
                local staffID = source;
                local gameLic = ids.license;
                local ip = ids.ip;
                local disc = ids.discord;
                local xbl = ids.xbl;
                local live = ids.live;
                local steamId = ids.steam;
                local userID = GetUserID(ip, disc, gameLic, steamId)
                local countWarns = MySQL.Sync.fetchScalar('SELECT Count(*) FROM Warns WHERE User_ID = ' .. userID);
                local countKicks = MySQL.Sync.fetchScalar('SELECT Count(*) FROM Kicks WHERE User_ID = ' .. userID);
                local countTempbans = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM Tempbans WHERE User_ID = ' .. userID);
                local punishment = "Warn";
                if countWarns >= 3 then 
                    punishment = "Kick";
                end
                if countKicks >= 3 then 
                    punishment = "Tempban1";
                end
                if countTempbans > 0 then 
                    punishment = "Tempban" .. (countTempbans + 1);
                end
                if countTempbans >= 3 then 
                    punishment = "Ban";
                end
                if punishment == 'Warn' then 
                    TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been warned by the ^6PUNISH CHAIN ^3by ^1"
            .. GetPlayerName(source) .. ' ^3for ^1' .. reason)
                    TriggerEvent('BadgerStaff:WarnPlayer', staffID, id, '[PUNISH CHAIN] ' ..
                        'Player ' .. GetPlayerName(id) .. " has been warned by the PUNISH CHAIN by " .. GetPlayerName(source)
                        .. ' for ' .. reason)
                elseif punishment == "Kick" then 
                    TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been punished by the ^6PUNISH CHAIN ^3by ^1"
            .. GetPlayerName(source) .. ' ^3for ^1' .. reason)
                    TriggerEvent('BadgerStaff:KickPlayer', staffID, id, '[PUNISH CHAIN] You have been kicked ' .. countKicks
                        .. ' times. Three of these will lead to a 4 hour Tempban... Reason: ' .. reason)
                elseif punishment == "Tempban1" then 
                    local now = os.time()
                    now = now + (60*60*4)
                    TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been punished by the ^6PUNISH CHAIN ^3by ^1"
            .. GetPlayerName(source) .. ' ^3for ^1' .. reason)
                    TriggerEvent('BadgerStaff:TempbanPlayer', staffID, id, now, '[PUNISH CHAIN] You have been kicked ' .. countKicks
                        .. ' times and have earned yourself a 4 hour tempban... Reason: ' .. reason, 4, "hours")
                elseif punishment == "Tempban2" then 
                    local now = os.time()
                    now = now + (60*60*24)
                    TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been punished by the ^6PUNISH CHAIN ^3by ^1"
            .. GetPlayerName(source) .. ' ^3for ^1' .. reason)
                    TriggerEvent('BadgerStaff:TempbanPlayer', staffID, id, now, '[PUNISH CHAIN] You have been tempbanned ' .. countTempbans
                        .. ' times and have earned yourself a 1 day tempban... Reason: ' .. reason, 1, "day")
                elseif punishment == "Tempban3" then
                    local now = os.time()
                    now = now + (60*60*24*7)
                    TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been punished by the ^6PUNISH CHAIN ^3by ^1"
            .. GetPlayerName(source) .. ' ^3for ^1' .. reason)
                    TriggerEvent('BadgerStaff:TempbanPlayer', staffID, id, now, '[PUNISH CHAIN] You have been tempbanned ' .. countTempbans
                        .. ' times and have earned yourself a 7 day tempban... Reason: ' .. reason, 7, "days")
                else
                    TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^5' .. GetPlayerName(id) .. " ^3has been punished by the ^6PUNISH CHAIN ^3by ^1"
            .. GetPlayerName(source) .. ' ^3for ^1' .. reason)
                    TriggerEvent('BadgerStaff:BanPlayer', staffID, id, "[PUNISH CHAIN] You have been tempbanned " .. countTempbans 
                        .. ' times and therefore have been permanently banned :( - Appeal: www.primenetwork.xyz') 
                end
            else 
                -- Not a valid player in the server with that ID 
                TriggerClientEvent('chatMessage', source, prefix .. '^1ERROR: There is no valid player in the server with that ID')
            end
        else 
            -- Not enough arguments, give proper usage 
            TriggerClientEvent('chatMessage', source, prefix .. '^1ERROR: Not proper usage. /chain <id> <reason>')
        end
    end
end)

RegisterCommand('history', function(source, args, rawCommand)
    -- This will show the past history of punishments for a user 
    if IsPlayerAceAllowed(source, "BadgerStaffPanel.Commands.History") then 
        if #args > 0 then 
            if GetPlayerIdentifiers(tonumber(args[1]))[1] ~= nil then 
                TriggerClientEvent('chatMessage', source, prefix .. 'Fetching their history...');
                local id = tonumber(args[1]) 
                local reason = table.concat(args, ' ')
                local ids = ExtractIdentifiers(id)
                local staffID = source;
                local gameLic = ids.license;
                local ip = ids.ip;
                local disc = ids.discord;
                local xbl = ids.xbl;
                local live = ids.live;
                local steamId = ids.steam;
                local userID = GetUserID(ip, disc, gameLic, steamId)
                local countNotes = MySQL.Sync.fetchScalar('SELECT Count(*) FROM Notes WHERE User_ID = ' .. userID);
                local countWarns = MySQL.Sync.fetchScalar('SELECT Count(*) FROM Warns WHERE User_ID = ' .. userID);
                local countKicks = MySQL.Sync.fetchScalar('SELECT Count(*) FROM Kicks WHERE User_ID = ' .. userID);
                local countTempbans = MySQL.Sync.fetchScalar('SELECT Count(*) FROM Tempbans WHERE User_ID = ' .. userID);
                local notes = MySQL.Sync.fetchAll('SELECT note FROM Notes WHERE User_ID = ' .. userID);
                local warns = MySQL.Sync.fetchAll('SELECT reason FROM Warns WHERE User_ID = ' .. userID);
                local kicks = MySQL.Sync.fetchAll('SELECT reason FROM Kicks WHERE User_ID = ' .. userID);
                local tempbans = MySQL.Sync.fetchAll('SELECT reason FROM Tempbans WHERE User_ID = ' .. userID);
                TriggerClientEvent('chatMessage', source, '      ^1' .. GetPlayerName(id) .. "'s History:")
                TriggerClientEvent('chatMessage', source, '^6NOTES: ^0' .. countNotes);
                for i = 1, #notes do 
                    TriggerClientEvent('chatMessage', source, '^6' .. notes[i].note);
                end
                TriggerClientEvent('chatMessage', source, '^5WARNS: ^0' .. countWarns);
                for i = 1, #warns do 
                    TriggerClientEvent('chatMessage', source, '^5' .. warns[i].reason);
                end
                TriggerClientEvent('chatMessage', source, '^3KICKS: ^0' .. countKicks);
                for i = 1, #kicks do 
                    TriggerClientEvent('chatMessage', source, '^3' .. kicks[i].reason);
                end
                TriggerClientEvent('chatMessage', source, '^1TEMPBANS: ^0' .. countTempbans);
                for i = 1, #tempbans do 
                    TriggerClientEvent('chatMessage', source, '^1' .. tempbans[i].reason);
                end
                TriggerClientEvent('chatMessage', source, prefix .. "All punishment history has been displayed...")
            else 
                -- Not a valid player in the server with that ID 
                TriggerClientEvent('chatMessage', source, prefix .. '^1ERROR: There is no valid player in the server with that ID')
            end
        else 
            -- Not enough arguments, give proper usage 
            TriggerClientEvent('chatMessage', source, prefix .. '^1ERROR: Not proper usage. /history <id>')
        end
    end
end)
function logPunishment(punish_type, punished_by_ID, ID_punished, data, Action_Date, Action) 
    -- Put this in the Logger SQL table 
    local dataStr = '';
    for k, v in pairs(data) do 
        dataStr = dataStr .. k .. ": " .. v .. " || ";
    end
    MySQL.Async.execute("INSERT INTO `Logger` VALUES (0, @punish_type, @punished_by_steam, " ..
        "@punished_ID, @data, @action_date, @action)", {['@punish_type'] = punish_type, 
        ['@punished_by_steam'] = punished_by_ID, ['@punished_ID'] = ID_punished,
         ['@data'] = dataStr, ['@action_date'] = Action_Date, ['@action'] = Action});
end
RegisterCommand('panelNote', function(source, args, rawCommand)
    -- /panelNote <userID> <discordPunisher> <note> 
    if source <= 0 then 
        local player = nil 
        local steamIDPlayer = nil
        local punishedID = tonumber(args[1])
        local discordPunisher = args[2]
        local reason = table.concat(args, ' '):gsub(args[1], ""):gsub(args[2], "")
        local punishedSteam = nil;
        for _, id in pairs(GetPlayers()) do 
            local ids = ExtractIdentifiers(id)
            local gameLic = ids.license;
            local ip = ids.ip;
            local disc = ids.discord;
            local xbl = ids.xbl;
            local live = ids.live;
            local userID = GetUserID(ip, disc, gameLic, sid)
            if tonumber(userID) == tonumber(punishedID) then 
                -- This is the user being punished: 
                player = id;
            end
        end
        local punisherID = GetUserIDByDiscord(discordPunisher)
        local punisherSteam = GetSteamFromUserID(punisherID)
        local punishedID = GetSteamFromUserID(punishedID)
        local punisherName = GetLastNameByID(punisherID)
        if player ~= nil then 
            -- They are in the server, we display the punish message 
            -- We don't do messages for notes 
        end
        -- We punish them below and log it:
        sendToDiscord('Note added via BadgerStaffPanel by ' .. punisherName, reason)
        MySQL.Async.execute("INSERT INTO `Notes` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = punishedID, ['@sid'] = punisherSteam, 
            ['@sidp'] = punishedSteam, ['@reason'] = reason})
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Note', punisherSteam, punishedID, data, action_date, 'Add');
    end 
end)
RegisterCommand('panelWarn', function(source, args, rawCommand)
    -- /panelWarn <userID> <discordPunisher> <reason>
    if source <= 0 then 
        local player = nil 
        local steamIDPlayer = nil
        local punishedID = tonumber(args[1])
        local discordPunisher = args[2]
        local reason = table.concat(args, ' '):gsub(args[1] .. " ", ""):gsub(args[2] .. " ", "")
        local punishedSteam = nil;
        for _, id in pairs(GetPlayers()) do 
            local ids = ExtractIdentifiers(id)
            local gameLic = ids.license;
            local ip = ids.ip;
            local disc = ids.discord;
            local xbl = ids.xbl;
            local live = ids.live;
            local sid = ids.steam;
            local userID = GetUserID(ip, disc, gameLic, sid)
            if tonumber(userID) == tonumber(punishedID) then 
                -- This is the user being punished: 
                player = id;
            end
        end
        local punisherID = GetUserIDByDiscord(discordPunisher)
        local punisherSteam = GetSteamFromUserID(punisherID)
        local punishedSteam = GetSteamFromUserID(punishedID)
        local lastName = GetLastNameByID(punisherID)
        if player ~= nil then 
            -- They are in the server, we display the punish message 
            -- We don't do messages for notes
            TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^1' .. GetPlayerName(player) .. '^3 has been warned for ^1' ..
                reason .. '^3 by ^1' .. lastName .. '^3 via ^1BadgerStaffPanel Client');
        end
        -- We punish them below and log it:
        MySQL.Async.execute("INSERT INTO `Warns` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = punishedID, 
            ['@sid'] = punisherSteam, ['@sidp'] = punishedSteam, 
            ['@reason'] = reason})
        sendToDiscord('Warn added via BadgerStaffPanel by ' .. lastName, reason)
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Warn', punisherSteam, punishedID, data, action_date, 'Add');
    end
end)
RegisterCommand('panelKick', function(source, args, rawCommand)
    -- /panelKick <userID> <discordPunisher> <reason>
    if source <= 0 then 
        local player = nil 
        local steamIDPlayer = nil
        local punishedID = tonumber(args[1])
        local discordPunisher = args[2]
        local reason = table.concat(args, ' '):gsub(args[1], ""):gsub(args[2], "")
        local punishedSteam = nil;
        for _, id in pairs(GetPlayers()) do 
            local ids = ExtractIdentifiers(id)
            local gameLic = ids.license;
            local ip = ids.ip;
            local disc = ids.discord;
            local xbl = ids.xbl;
            local live = ids.live;
            local sid = ids.steam;
            local userID = GetUserID(ip, disc, gameLic, sid)
            if tonumber(userID) == tonumber(punishedID) then 
                -- This is the user being punished: 
                player = id;
            end
        end
        local punisherID = GetUserIDByDiscord(discordPunisher)
        local punisherSteam = GetSteamFromUserID(punisherID)
        local punishedSteam = GetSteamFromUserID(punishedID)
        local lastName = GetLastNameByID(punisherID)
        if player ~= nil then 
            -- They are in the server, we display the punish message 
            -- We don't do messages for notes 
            TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^1' .. GetPlayerName(player) .. '^3 has been kicked for ^1' ..
            reason .. '^3 by ^1' .. lastName .. '^3 via ^1BadgerStaffPanel Client');
            DropPlayer(player, '[BadgerStaffPanel] You have been kicked for ' .. reason .. ' by ' .. lastName .. ' via BadgerStaffPanel Client');
        end
        -- We punish them below and log it:
        MySQL.Async.execute("INSERT INTO `Kicks` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = punishedID, 
            ['@sid'] = punisherSteam, ['@sidp'] = punishedSteam, 
            ['@reason'] = reason})
        sendToDiscord('Kick added via BadgerStaffPanel by ' .. lastName, reason)
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Kick', punisherSteam, punishedID, data, action_date, 'Add');
    end
end)
RegisterCommand('panelTempban', function(source, args, rawCommand)
    -- /panelTempban <userID> <time> <discordPunisher> <reason>
    -- <time> == 1h, 1d, 1w, 1m === 1 hour, 1 day, 1 week, 1 month  
    if source <= 0 then 
        local player = nil 
        local steamIDPlayer = nil
        local punishedID = tonumber(args[1])
        local timeInput = args[2]
        local discordPunisher = args[3]
        local reason = table.concat(args, ' '):gsub(args[1], ""):gsub(args[2], ""):gsub(args[3], "")
        local punishedSteam = nil;
        local timeNum = args[2]:gsub("m", ""):gsub("d", ""):gsub("h", ""):gsub("s", "")
        local timeType = tostring(args[2]:sub(-1))
        local now = os.time()
        local startDate = os.date("%c", now)
        local endDate = now 
        local timeStr = ""
        if timeType == 'm' then 
            -- It's months 
            endDate = endDate + (60*60*24*30*timeNum)
            timeStr = "month"
        elseif timeType == 'd' then 
            -- It's days 
            endDate = endDate + (60*60*24*timeNum)
            timeStr = "day"
        elseif timeType == 'h' then 
            -- It's hours 
            endDate = endDate + (60*60*timeNum)
            timeStr = "hour"
        elseif timeType == "s" then 
            endDate = endDate + timeNum
            timeStr = "second"
        end
        for _, id in pairs(GetPlayers()) do 
            local ids = ExtractIdentifiers(id)
            local gameLic = ids.license;
            local ip = ids.ip;
            local disc = ids.discord;
            local xbl = ids.xbl;
            local live = ids.live;
            local sid = ids.steam;
            local userID = GetUserID(ip, disc, gameLic, sid)
            if tonumber(userID) == tonumber(punishedID) then 
                -- This is the user being punished: 
                player = id;
            end
        end
        local punisherID = GetUserIDByDiscord(discordPunisher)
        local punisherSteam = GetSteamFromUserID(punisherID)
        local punishedSteam = GetSteamFromUserID(punishedID)
        local lastName = GetLastNameByID(punisherID)
        if player ~= nil then 
            -- They are in the server, we display the punish message 
            TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^1' .. GetPlayerName(player) .. '^3 has been temporarily banned for ^1' ..
            timeNum .. timeStr .. "(s)" .. '^3 by ^1' .. lastName .. ' ^3for ^1' .. reason .. '^3 via ^1BadgerStaffPanel Client');
            DropPlayer(player, '[BadgerStaffPanel] You have been temporarily banned for ' .. timeNum .. timeStr .. "(s)" 
            .. ' by ' .. lastName .. 'for ' .. reason .. ' via BadgerStaffPanel Client');
        end
        -- We punish them below and log it:
        MySQL.Async.execute("INSERT INTO `Tempbans` VALUES (@id, @sid, @sidp, @reason, @end, 0)", {['@id'] = punishedID, 
            ['@sid'] = punisherSteam, ['@sidp'] = punishedSteam, 
            ['@reason'] = reason, ['@end'] = endDate})
        sendToDiscord('Tempban added via BadgerStaffPanel by ' .. lastName, reason)
        local data = {
            ['reason'] = reason,
            ['endDate'] = endDate
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Tempban', punisherSteam, punishedID, data, action_date, 'Add');
    end
end)
RegisterCommand('panelBan', function(source, args, rawCommand)
    -- /panelBan <userID> <discordPunisher> <reason>
    if source <= 0 then 
        local player = nil 
        local steamIDPlayer = nil
        local punishedID = tonumber(args[1])
        local discordPunisher = args[2]
        local reason = table.concat(args, ' '):gsub(args[1], ""):gsub(args[2], "")
        local punishedSteam = nil;
        for _, id in pairs(GetPlayers()) do 
            local ids = ExtractIdentifiers(id)
            local gameLic = ids.license;
            local ip = ids.ip;
            local disc = ids.discord;
            local xbl = ids.xbl;
            local live = ids.live;
            local sid = ids.steam;
            local userID = GetUserID(ip, disc, gameLic, sid)
            if tonumber(userID) == tonumber(punishedID) then 
                -- This is the user being punished: 
                player = id;
            end
        end
        local punisherID = GetUserIDByDiscord(discordPunisher)
        local punisherSteam = GetSteamFromUserID(punisherID)
        local punishedSteam = GetSteamFromUserID(punishedID)
        local lastName = GetLastNameByID(punisherID)
        if player ~= nil then 
            -- They are in the server, we display the punish message 
            -- We don't do messages for notes 
            TriggerClientEvent('chatMessage', -1, prefix .. 'Player ^1' .. GetPlayerName(player) .. '^3 has been banned for ^1' ..
            reason .. '^3 by ^1' .. lastName .. '^3 via ^1BadgerStaffPanel Client');
            DropPlayer(player, '[BadgerStaffPanel] You have been banned for ' .. reason .. ' by ' .. lastName .. ' via BadgerStaffPanel Client');
        end
        -- We punish them below and log it:
        MySQL.Async.execute("INSERT INTO `Bans` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = punishedID, 
            ['@sid'] = punisherSteam, ['@sidp'] = punishedSteam, 
            ['@reason'] = reason})
        sendToDiscord('Ban added via BadgerStaffPanel by ' .. lastName, reason)
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Ban', punisherSteam, punishedID, data, action_date, 'Add');
    end
end)

function sendToDiscord(title, msg)
    local embed = {}
    embed = {
        {
            ["color"] = 16711680,
            ["title"] = "**".. title .."**",
            ["description"] = msg,
            ["footer"] = {
                ["text"] = "",
            },
        }
    }
    PerformHttpRequest(discordWebHook, 
    function(err, text, headers) end, 'POST', json.encode({username = name, embeds = embed}), { ['Content-Type'] = 'application/json' })
end

function ExtractIdentifiers(src)
    local identifiers = {
        steam = "",
        ip = "",
        discord = "",
        license = "",
        xbl = "",
        live = ""
    }

    --Loop over all identifiers
    for i = 0, GetNumPlayerIdentifiers(src) - 1 do
        local id = GetPlayerIdentifier(src, i)

        --Convert it to a nice table.
        if string.find(id, "steam") then
            identifiers.steam = id
        elseif string.find(id, "ip") then
            identifiers.ip = id
        elseif string.find(id, "discord") then
            identifiers.discord = id
        elseif string.find(id, "license") then
            identifiers.license = id
        elseif string.find(id, "xbl") then
            identifiers.xbl = id
        elseif string.find(id, "live") then
            identifiers.live = id
        end
    end

    return identifiers
end

-- HANDLES SQL --
----[[
bansList = {}
tempbansList = {}
function GetUserIDByDiscord(disc)
    local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE (`discord` = @disc)', {['@disc'] = disc});
    local userID = nil 
    for i=1, #rows do 
        userID = rows[i].ID 
    end
    return userID;
end
function GetLastNameByID(userID) 
    local rows = MySQL.Sync.fetchAll('SELECT `lastPlayerName` FROM `Users` WHERE `ID` = @userID', {['@userID'] = userID})
    local lastPlayerName = nil  
    for i=1, #rows do 
        lastPlayerName = rows[i].lastPlayerName
    end
    return lastPlayerName
end
function GetSteamFromUserID(userID)
    local rows = MySQL.Sync.fetchAll('SELECT `steamID` FROM `Users` WHERE `ID` = @userID', {['@userID'] = userID})
    local steamID = nil 
    for i=1, #rows do 
        steamID = rows[i].steamID
    end
    return steamID 
end
function GetUserID(ip, disc, gameLic, sid)
    local userID = nil  
    local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE (`gameLicense` = @gameLic '.. 
        'OR `steamID` = @sid) AND ' .. 
        '`ID` != 0', {
        ['@gameLic'] = gameLic, ['@sid'] = sid
    })
    for i=1, #rows do 
        userID = rows[i].ID
    end

    return userID;
end
bannedAlready = {}
AddEventHandler('playerConnecting', function(playerName, kickReason, deferrals)
    -- Don't allow them to connect if they have a ban on them 
    deferrals.defer()
    local steamId = ExtractIdentifiers(source).steam 

    -- Check for steamID 
    if steamId == "" or steamId == nil then 
        -- You need Steam opened in the background to play on our server 
        deferrals.done('[BadgerStaffPanel] You need the Steam client opened in the background to login to this server...')
        return;
    end
    --[[
        Users:
            ID, steamID, lastPlayerName, gameLicense, live, xbl, discord, ip

        Primaries:
            steamID, gameLicense, IP
    ]]--
    local ids = ExtractIdentifiers(source)
    local gameLic = ids.license;
    local banMessage = '';
    local wasBanned = false;
    if bannedAlready[gameLic] == nil then 
        local ip = ids.ip;
        local disc = ids.discord;
        local xbl = ids.xbl;
        local live = ids.live;
        local userID = nil;
        userID = GetUserID(ip, disc, gameLic, steamId)
        --print("The userID is " .. tostring(userID))
        if userID ~= nil then  
            -- Add them to Users in SQL 
            local alreadyBanned = false 
            local banned = false;
            local rows = MySQL.Sync.fetchAll('SELECT * FROM Bans WHERE User_ID = @userID', 
            {
            ['@userID'] = userID
            });
            local reason = '';
            for i=1, #rows do 
                banned = true;
                reason = rows[i].reason;
                bannedAlready[gameLic] = {reason, nil};
            end
            if banned == true then 
                -- They are banned 
                print("User should be permanently banned...")
                -- Kick them out 
                -- MSG: 'You were permanently banned from this server for ' .. reason
                --deferrals.done('[BadgerStaffPanel] You were permanently banned from this server for: ' .. reason)
                banMessage = '[BadgerStaffPanel] You were permanently banned from this server for: ' .. reason;
                wasBanned = true;
                alreadyBanned = true 
            end 
            -- Check tempban:
            if not alreadyBanned then 
                local banned = false;
                local rows = MySQL.Sync.fetchAll('SELECT * FROM Tempbans WHERE User_ID = @userID', 
                {
                ['@userID'] = userID
                });
                local reason = '';
                local time = nil;
                for i=1, #rows do 
                    banned = true;
                    reason = rows[i].reason;
                    time = rows[i].endDate;
                end
                if banned == true then 
                    -- They are banned
                    -- Kick them out if time is not expired:
                    if os.time() < time then 
                        local timeStr = os.date('%c', time)
                        --deferrals.done('[BadgerStaffPanel] You were temporarily banned from this server for: ' .. reason .. " until " .. timeStr)
                        banMessage = '[BadgerStaffPanel] You were temporarily banned from this server for: ' .. reason .. " until " .. timeStr;
                        bannedAlready[gameLic] = {reason, time};
                        wasBanned = true;
                    else 
                        -- Not banned anymore, don't need to defer them 
                    end
                end
            end
        end -- End rows ~= nil statement 
    else
        -- Already banned, check if time expired and remove 
        print('[BadgerStaffPanel] bannedAlready array triggered...');
        wasBanned = true;
        local timeBannedTill = bannedAlready[gameLic][2];
        local reason = bannedAlready[gameLic][1];
        if timeBannedTill ~= nil then 
            if os.time() <= timeBannedTill then 
                bannedAlready[gameLic] = nil;
                wasBanned = false;
                deferrals.update('Your ban has expired! Congratulations!');
                print('[BadgerStaffPanel] User [' .. GetPlayerName(source) .. '] was unbanned as their time has passed...')
            else 
                local timeStr = os.date('%c', timeBannedTill)
                banMessage = '[BadgerStaffPanel] You were temporarily banned from this server for: ' .. reason .. " until " .. timeStr;
            end
        else
            -- Banned permanently 
            banMessage = '[BadgerStaffPanel] You were permanently banned from this server for: ' .. reason;
        end
    end
    if wasBanned then 
        deferrals.done(banMessage);
        return;
    else 
        local userID = nil;
        local ip = ids.ip;
        local disc = ids.discord;
        local xbl = ids.xbl;
        local live = ids.live;
        local userID = nil;
        userID = GetUserID(ip, disc, gameLic, steamId)
        if userID == nil then 
            MySQL.Async.execute('INSERT INTO `Users` VALUES (@ID, @sid, @name, @gameLic, @live, @xbl, @disc, @ip) ' .. 
                    'ON DUPLICATE KEY UPDATE lastPlayerName = @name;', {['@sid'] = steamId, ['@name'] = playerName, 
                    ['@ID'] = 0, ['@gameLic'] = gameLic, ['@live'] = live, ['@xbl'] = xbl, ['@disc'] = disc, ['@ip'] = ip})
            print("[BadgerStaffPanel] Player " .. GetPlayerName(source) .. " has been inserted into the database..." );
        else 
            -- Update their name, gameLic, live, xbl, disc, ip:
            MySQL.Async.execute('UPDATE `Users` SET `steamID` = @sid, ' ..
                '`lastPlayerName` = @name, `gameLicense` = @gameLic, `live` = @live, `xbl` = @xbl, `discord` = @disc, ' ..
                '`ip` = @ip WHERE `ID` = @ID', {['@sid'] = steamId, ['@name'] = playerName, 
                    ['@ID'] = userID, ['@gameLic'] = gameLic, ['@live'] = live, ['@xbl'] = xbl, ['@disc'] = disc, ['@ip'] = ip})
                    print("[BadgerStaffPanel] Player " .. GetPlayerName(source) .. " has had their information updated in the database..." );
        end
    end
    deferrals.done()
end)
MySQL.ready(function()
    --[[
        SQL Tables:
            Bans:
                User_ID, steamIdStaff, steamIdPlayer, reason, uid

            Kicks: 
                User_ID, steamIdStaff, steamIdPlayer, reason, uid

            Notes:
                User_ID, steamIdStaff, steamIdPlayer, note, uid

            Tempbans:
                User_ID, steamIdStaff, steamIdPlayer, reason, endDate, uid

            Warns:
                User_ID, steamIdStaff, steamIdPlayer, reason, uid

            Users:
                ID, steamID, lastPlayerName, gameLicense, live, xbl, discord, ip
    --]]--
    ----[[
    AddEventHandler('BadgerStaff:NotePlayer', function(staffMem, player, note)
        -- Handle
        local staffID = ExtractIdentifiers(staffMem).steam 
        local playerID = ExtractIdentifiers(player).steam
        local user_ID = nil 
        local gameLic = ExtractIdentifiers(player).license 
        sendToDiscord("Player " .. GetPlayerName(player) .. " was noted by " .. GetPlayerName(staffMem), note)
        local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE `gameLicense` = \'' .. gameLic .. '\';', {})
        for i=1, #rows do 
            user_ID = rows[i].ID 
        end
        MySQL.Async.execute("INSERT INTO `Notes` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = user_ID, ['@sid'] = staffID, ['@sidp'] = playerID, 
        ['@reason'] = note})
        local data = {
            ['note'] = note,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Note', staffID, user_ID, data, action_date, 'Add');
    end)

    AddEventHandler('BadgerStaff:WarnPlayer', function(staffMem, player, reason)
        -- Handle
        local staffID = 0
        if staffMem ~= -1 then 
            staffID = ExtractIdentifiers(staffMem).steam 
        end
        local playerID = ExtractIdentifiers(player).steam
        local user_ID = nil 
        local lic = ExtractIdentifiers(player).license 
        sendToDiscord("Player " .. GetPlayerName(player) .. " was warned by " .. GetPlayerName(staffMem), reason)
        local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE `gameLicense` = \'' .. lic .. '\';', {})
        for i=1, #rows do 
            user_ID = rows[i].ID 
        end
        MySQL.Async.execute("INSERT INTO `Warns` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = user_ID, ['@sid'] = staffID, ['@sidp'] = playerID, 
        ['@reason'] = reason})
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Warn', staffID, user_ID, data, action_date, 'Add');
    end)

    AddEventHandler('BadgerStaff:KickPlayer', function(staffMem, player, reason)
        -- Handle
        local staffID = 0
        if staffMem ~= -1 then 
            staffID = ExtractIdentifiers(staffMem).steam 
        end
        local playerID = ExtractIdentifiers(player).steam
        local user_ID = nil 
        local lic = ExtractIdentifiers(player).license 
        sendToDiscord("Player " .. GetPlayerName(player) .. " was kicked by " .. GetPlayerName(staffMem), reason)
        local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE `gameLicense` = \'' .. lic .. '\';', {})
        for i=1, #rows do 
            user_ID = rows[i].ID 
        end
        DropPlayer(player, "You have been kicked by " .. GetPlayerName(staffMem) .. " for " .. reason)
        MySQL.Async.execute("INSERT INTO `Kicks` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = user_ID, ['@sid'] = staffID, ['@sidp'] = playerID, 
        ['@reason'] = reason})
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Kick', staffID, user_ID, data, action_date, 'Add');
    end)

    AddEventHandler('BadgerStaff:TempbanPlayer', function(staffMem, player, time, reason, timeNum, timeStr)
        -- Handle
        local staffID = 0
        if staffMem ~= -1 then 
            staffID = ExtractIdentifiers(staffMem).steam 
        end
        local playerID = ExtractIdentifiers(player).steam
        local user_ID = nil 
        local lic = ExtractIdentifiers(player).license 
        sendToDiscord("Player " .. GetPlayerName(player) .. " was tempbanned by " .. GetPlayerName(staffMem) .. " for " .. tostring(timeNum) ..
            " " .. timeStr, reason)
        local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE `gameLicense` = \'' .. lic .. '\';', {})
        for i=1, #rows do 
            user_ID = rows[i].ID 
        end
        DropPlayer(player, "[BadgerStaffPanel] You have been temporarily banned by " .. GetPlayerName(staffMem) .. " for " .. reason .. " (" 
            .. tostring(timeNum) .. " " .. timeStr .. "(s)")
        MySQL.Async.execute("INSERT INTO `Tempbans` VALUES (@id, @sid, @sidp, @reason, @endDate, 0)", {['@id'] = user_ID, 
            ['@sid'] = staffID, ['@sidp'] = playerID, 
        ['@reason'] = reason, ['endDate'] = tonumber(time)})
        local data = {
            ['reason'] = reason,
            ['endDate'] = time,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Tempban', staffID, user_ID, data, action_date, 'Add');
    end)

    AddEventHandler('BadgerStaff:BanPlayer', function(staffMem, player, reason)
        -- Handle
        local staffID = 0
        if staffMem ~= -1 then 
            staffID = ExtractIdentifiers(staffMem).steam 
        end
        local playerID = ExtractIdentifiers(player).steam
        local user_ID = nil 
        local lic = ExtractIdentifiers(player).license 
        sendToDiscord("Player " .. GetPlayerName(player) .. " was banned by " .. GetPlayerName(staffMem), reason)
        local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE `gameLicense` = \'' .. lic .. '\';', {})
        for i=1, #rows do 
            user_ID = rows[i].ID 
        end
        DropPlayer(player, "[BadgerStaffPanel] You have been permanently banned by " .. GetPlayerName(staffMem) .. " for " .. reason)
        MySQL.Async.execute("INSERT INTO `Bans` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = user_ID, ['@sid'] = staffID, ['@sidp'] = playerID, 
        ['@reason'] = reason})
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
        logPunishment('Ban', staffID, user_ID, data, action_date, 'Add');
    end)
    AddEventHandler('BadgerStaff:Anticheat:BanPlayer', function(player, reason)
        -- Handle
        local playerID = ExtractIdentifiers(player).steam
        local user_ID = nil 
        local lic = ExtractIdentifiers(player).license 
        sendToDiscord("Player " .. GetPlayerName(player) .. " was banned by Badger-ANTICHEAT", reason);
        local rows = MySQL.Sync.fetchAll('SELECT `ID` FROM `Users` WHERE `gameLicense` = \'' .. lic .. '\';', {})
        for i=1, #rows do 
            user_ID = rows[i].ID 
        end
        DropPlayer(player, "[BadgerStaffPanel] You have been permanently banned by " .. "Badger-ANTICHEAT" .. " for " .. reason)
        MySQL.Async.execute("INSERT INTO `Bans` VALUES (@id, @sid, @sidp, @reason, 0)", {['@id'] = user_ID, ['@sid'] = 'Anticheat', ['@sidp'] = playerID, 
        ['@reason'] = reason})
        local data = {
            ['reason'] = reason,
        };
        local action_date = os.date('%m/%d/%Y %I:%M:%S %p');
    end)
end)
--]]--
