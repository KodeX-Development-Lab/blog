import { IconButton, ButtonGroup, Button, Icon, Box } from "@mui/material";
import {
    ThumbUpOffAlt as LikeIcon
} from "@mui/icons-material";

import { useNavigate } from "react-router-dom";
import { useApp } from "../ThemedApp";
import { CommentType, PostType } from "../types/types";

export default function ReactionButton({ type, post, comment, myReaction, reactionBrief, reactionsCount, doReaction }: { type: 'post' | 'comment', post?: PostType, comment?: CommentType, myReaction: object | null, reactionBrief: [], reactionsCount: number, doReaction: (id: string) => void }) {
    const navigate = useNavigate();
    const { reactionTypes, defaultReactionType } = useApp();

    return (
        <Box sx={{ m: 2, pt: 3 }}>
            {
                reactionTypes && reactionTypes.map(reactionType => {
                    return (
                        <IconButton
                            key={reactionType.id}
                            size="small"
                            onClick={e => {
                                e.stopPropagation();
                                doReaction(reactionType.id)
                            }}>

                            {(myReaction && myReaction.reaction_type.id == reactionType.id) ?
                                <img style={{ width: "25px", height: "auto" }} src={reactionType.reacted_icon} alt="" />
                                :
                                <img style={{ width: "25px", height: "auto" }} src={reactionType.icon} alt="" />
                            }
                        </IconButton>
                    )
                })
            }

            <Box sx={{
                display: "flex",
                justifyContent: "start",
                mb: 3
            }}
                onClick={e => {
                    e.stopPropagation();
                    if (type == 'post') {
                        navigate(`/posts/${post?.id}/reactions`);
                    } else {
                        navigate(`/posts/${comment?.postId}/comments/${comment?.id}/reactions`);
                    }
                }}
            >
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