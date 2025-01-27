import { IconButton, ButtonGroup, Button, Icon, Box } from "@mui/material";
import {
    ThumbUpOffAlt as LikeIcon
} from "@mui/icons-material";

import { useNavigate } from "react-router-dom";
import { useApp } from "../ThemedApp";

export default function ReactionButton({ myReaction, reactionBrief, reactionsCount, doReaction }: { myReaction: object | null, reactionBrief: [], reactionsCount: number, doReaction: (id: string) => void }) {
    const navigate = useNavigate();
    const { reactionTypes, defaultReactionType } = useApp();

    return (
        <Box sx={{ m: 2, pt: 3 }}>
            {
                myReaction ? <IconButton
                    size="small"
                    onClick={e => {
                        e.stopPropagation();
                        doReaction(myReaction.reaction_type.id)
                    }}>
                    <img style={{ width: "25px", height: "auto" }} src={myReaction.reaction_type.reacted_icon} alt="" />

                </IconButton>
                    :
                    defaultReactionType ?
                        <IconButton
                            size="small"
                            onClick={e => {
                                e.stopPropagation();
                                doReaction(defaultReactionType.id)
                            }}>
                            <img style={{ width: "25px", height: "auto", color: "white" }} src={defaultReactionType.icon} alt="" />
                        </IconButton>
                        :
                        <LikeIcon />
            }

            <Box sx={{
                display: "flex",
                justifyContent: "start",
                mb: 3
            }}>

                {
                    reactionBrief.map(reaction => {
                        return (
                            <img key={reaction.id} style={{ width: "25px", height: "auto" }} src={reaction.reactedIcon} alt="" />
                        );
                    })
                }
                {
                    reactionsCount > 0 &&
                    (
                        <Button
                            onClick={e => {
                                navigate(``);
                                e.stopPropagation();
                            }}
                            sx={{ color: "text.fade" }}
                            variant="text"
                            size="small">
                            {reactionsCount}
                        </Button>
                    )
                }
            </Box>

        </Box >
    );
}