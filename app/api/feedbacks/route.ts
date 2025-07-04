import { feedbackDb } from "@/lib/database"
import { NextResponse } from "next/server"

export async function GET() {
  try {
    const feedbacks = feedbackDb.getApprovedFeedback()
    return NextResponse.json(feedbacks)
  } catch (error) {
    return NextResponse.json({ error: "Failed to fetch feedbacks" }, { status: 500 })
  }
}
