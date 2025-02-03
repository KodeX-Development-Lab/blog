import { Box, Alert } from "@mui/material";
import { useParams, useSearchParams } from "react-router-dom";
import { fetchCommentBriefReactions, fetchCommentReactions, fetchPostBriefReactions, fetchPostReactions } from "../libs/fetcher";
import Reaction from "../components/Reactions";
import { useQuery } from "react-query";
import { useState } from "react";

export default function CommentReactions() {
	const { id, commentId } = useParams();
	const [searchParams, setSearchParams] = useSearchParams();
	const [reactionType, setReactionType] = useState(searchParams.get("reaction_type") ?? '');
	const newQueryParameters : URLSearchParams = new URLSearchParams();

	const { isLoading, isError, error, data } = useQuery("postReactionsBrief", () => fetchCommentBriefReactions(id, commentId));
	const { data: postReactions } = useQuery(["postReactions", reactionType], () => fetchCommentReactions(id, commentId, reactionType));

	const switchTab = (reactionType: string) => {
		setReactionType(reactionType);
		newQueryParameters.set('reaction_type',  reactionType);
		setSearchParams(newQueryParameters);	
	}

	if (isError) {
		return (
			<Box>
				<Alert severity="warning">{error?.message}</Alert>
			</Box>
		);
	}

	if (isLoading) {
		return <Box sx={{ textAlign: "center" }}>Loading...</Box>;
	}

	return (
		<>
			<Reaction
			 reactionBrief={data.data.reactionsCounts} 
			 reactions={postReactions?.data?.reactions ?? []} 
			 switchTab={switchTab}
			 />
		</>
	);
}