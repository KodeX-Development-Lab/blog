import { Avatar, Box, Button, ButtonGroup, ListItem, ListItemAvatar, ListItemButton, ListItemIcon, ListItemSecondaryAction, ListItemText, Tab, Tabs } from "@mui/material";
import { useState } from "react";
import List from "../List";
import { useParams } from "react-router-dom";
import { purple } from "@mui/material/colors";

interface TabPanelProps {
    children?: React.ReactNode;
    index: number;
    value: number;
}

function CustomTabPanel(props: TabPanelProps) {
    const { children, value, index, ...other } = props;

    return (
        <div
            role="tabpanel"
            hidden={value !== index}
            id={`simple-tabpanel-${index}`}
            aria-labelledby={`simple-tab-${index}`}
            {...other}
        >
            {value === index && <Box sx={{ p: 3 }}>{children}</Box>}
        </div>
    );
}

function a11yProps(index: number) {
    return {
        id: `simple-tab-${index}`,
        'aria-controls': `simple-tabpanel-${index}`,
    };
}


export default function Reaction({ reactionBrief, reactions, switchTab }: { reactionBrief: [], reactions: [], switchTab: (reaction_type: number | string) => void }) {
    const [selectedReactionType, setSelectedReactionType] = useState<string | number>("");

    const handleChange = (newValue: string | number) => {
        setSelectedReactionType(newValue);
        switchTab(newValue);
    };

    const content = reactions.map(item => {
        return (
            <ListItem key={item.id}>
                <ListItemButton>
                    <ListItemAvatar>
                        <Avatar />
                    </ListItemAvatar>
                    <ListItemText
                        primary={item.user.name}
                        secondary={item.user.bio}
                    />
                    <ListItemIcon>
                        <img style={{ width: "25px", height: "auto" }} src={item.reactionType.reacted_icon} alt="" />
                    </ListItemIcon>
                </ListItemButton>
            </ListItem>
        );
    });

    return (
        <Box sx={{ width: '100%' }}>
            <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                <ButtonGroup variant="text" aria-label="Basic button group">
                    {
                        reactionBrief.map((reactionCount) => {
                            return (
                                <Button
                                    key={reactionCount.id}
                                    onClick={() => handleChange(reactionCount.id)}
                                    style={reactionCount.id == selectedReactionType ? { color: "white" } : {}}
                                >
                                    {`${reactionCount.name} - ${reactionCount.count}`}
                                </Button>
                            )
                        })
                    }
                </ButtonGroup>

            </Box>
            {content}
        </Box>
    );
}