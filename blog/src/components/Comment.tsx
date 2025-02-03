import { Box, Card, CardContent, Typography, IconButton } from "@mui/material";

import {
	Alarm as TimeIcon,
	AccountCircle as UserIcon,
	Delete as DeleteIcon,
} from "@mui/icons-material";

import { useNavigate } from "react-router-dom";

import { green } from "@mui/material/colors";

import { formatRelative } from "date-fns";
import { CommentType } from "../types/types";
import ReactionButton from "./ReactionButton";
import { useMutation } from "react-query";
import { reactComment } from "../libs/fetcher";
import { queryClient } from "../ThemedApp";

interface CommentProps {
	comment: CommentType,
	remove: (id: string | number) => void,
	primary: boolean,
}

export default function Comment({ comment, remove, primary }: CommentProps) {
	const navigate = useNavigate();

	const doReaction = useMutation(async (reactionTypeId) => reactComment(comment.postId, comment.id, reactionTypeId), {
		onSuccess: async () => {
			queryClient.refetchQueries("comments");
		},
	});

	return (
		<Card sx={{ mb: 2 }}>
			{primary && <Box sx={{ height: 50, bgcolor: green[500] }} />}

			<CardContent
				sx={{ cursor: "pointer" }}>
				<Box
					sx={{
						display: "flex",
						flexDirection: "row",
						justifyContent: "space-between",
					}}>
					<Box
						sx={{
							display: "flex",
							flexDirection: "row",
							alignItems: "center",
							gap: 1,
						}}>
						<TimeIcon
							fontSize="10"
							color="success"
						/>
						<Typography
							variant="caption"
							sx={{ color: green[500] }}>
							{formatRelative(comment.createdAt, new Date())}
						</Typography>
					</Box>
					{
						comment.canDelete &&
						<IconButton
							sx={{ color: "text.fade" }}
							size="small"
							onClick={e => {
								remove(comment.id);
								e.stopPropagation();
							}}>
							<DeleteIcon
								color="inherit"
								fontSize="inherit"
							/>
						</IconButton>
					}
				</Box>

				<Typography sx={{ my: 3 }}>{comment.content}</Typography>

				<ReactionButton
					type='comment'
					comment={comment}
					myReaction={comment.myReaction}
					reactionBrief={comment.reactionBrief}
					reactionsCount={comment.reactionsCount}
					doReaction={doReaction.mutate}
				/>

				<Box
					sx={{
						display: "flex",
						flexDirection: "row",
						alignItems: "center",
						gap: 1,
					}}>
					<UserIcon
						fontSize="12"
						color="info"
					/>
					<Typography variant="caption">{comment.user.name}</Typography>
				</Box>
			</CardContent>
		</Card>
	);
}