<?php

namespace App\Http\Requests;

use App\Rules\AskIfParentQuestionIsBeforeValidation;
use App\Rules\HasAtleastOneConditionValidation;
use App\Rules\SkipToQuestionAfterValidation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $sections
 */
class StoreSurveyRequest extends FormRequest
{
    private $allQuestions = [];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth())
        {
            // dd($this->all());
            return true;
        }
        return false;
    }

    public function prepareForValidation(): void
    {
        $sections = $this->sections;
        foreach ($sections as $section)
        {
            $this->allQuestions[] = $section["questions"];
        }
        $this->allQuestions = collect($this->allQuestions)->flatten(1);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            "id" => "required|uuid",
            "title" => "required|string",
            "subTitle" => "required|string",
            "description" => "nullable|string",
            "userId" => "required|integer|exists:users,id",
            "organisationId" => ($this->isTemplate == false) ? "required|integer|exists:organisations,id" : "nullable",
            "note" => "nullable|string",
            "isPersonalInfoQuestionAsked" => "required|boolean",

            // Sections
            // "sections" => "min:1",
            "sections.*.id" => "required|uuid",
            "sections.*.title" => "required|string",
            "sections.*.position" => "required|integer|gt:0",

            // Questions
            "sections.*.questions" => "min:1",
            "sections..questions..id" => "required|uuid",
            "sections..questions..questionNumber" => "required|integer|gt:0",
            "sections..questions..type" => "required|exists:question_types,id",
            "sections..questions..statement" => "required|string",
            "sections..questions..subTitle" => "required|string",
            "sections..questions..uniqueAttributeId" => "nullable|exists:unique_attributes,id",
            "sections..questions..required" => "required_unless:sections..questions..type,8|boolean",
            "sections..questions..comment" => "required_unless:sections..questions..type,8|boolean",
            "sections..questions..image" => "required_unless:sections..questions..type,8|boolean",
            "sections..questions..hasConditions" => "required|boolean",

            // Single and Multiple Questions (Other & Options (Options Validation for Likert And Matrix as well))
            "sections..questions..other" => "required_if:sections..questions..type,1,2|boolean",
            "sections..questions..options" => "required_if:sections..questions..type,1,2,6,7|array|min:2",
            "sections..questions..options..id" => "required_if:sections..questions.*.type,1,2,6,7|uuid",
            "sections..questions..options..position" => "required_if:sections..questions.*.type,1,2,6,7|integer",
            "sections..questions..options..value" => "required_if:sections..questions.*.type,1,2,6,7|string",
            "sections..questions..options..score" => "required_if:sections..questions.*.type,1,2,6,7|integer",
            "sections..questions..options.*.comment" => "nullable|string",

            // NPS / Rating Questions
            "sections..questions..maxRating" => "required_if:sections..questions..type,4,5|integer|min:5,max:10",
            "sections..questions..lowText" => "required_if:sections..questions..type,4,5|string",
            "sections..questions..midText" => "required_if:sections..questions..type,4,5|string",
            "sections..questions..highText" => "required_if:sections..questions..type,4,5|string",

            // Matrix / Likert
            "sections..questions..subQuestions" => "required_if:sections..questions..type,6,7|array|min:2",
            "sections..questions..subQuestions..id" => "required_if:sections..questions.*.type,6,7|uuid",
            "sections..questions..subQuestions..position" => "required_if:sections..questions.*.type,6,7|integer",
            "sections..questions..subQuestions..statement" => "required_if:sections..questions.*.type,6,7|string",
            "sections..questions..subQuestions..type" => "required_if:sections..questions.*.type,6,7|string|in:subQuestion",
            "sections..questions..subQuestions..comment" => "required_if:sections..questions.*.type,6,7|boolean",
            "sections..questions..subQuestions.*.uniqueAttribute" => "nullable|exists:unique_attributes,id",
            "sections..questions..optionsCount" => "required_if:sections..questions..type,6,7|integer|min:2"

        ];

        // Validation For Conditions
        foreach ($this->sections as $sectionIndex => $section)
        {
            foreach ($section["questions"] as $questionIndex => $question)
            {
                $base = "sections.$sectionIndex.questions.$questionIndex.conditions";
                $baseAskIf = "sections.$sectionIndex.questions.$questionIndex.conditions.ask_if.*";
                $baseSkipTo = "sections.$sectionIndex.questions.$questionIndex.conditions.skip_to.*";
                if ($question["hasConditions"])
                {
                    // Base Rules For Both Type Of Conditions
                    $rules = array_merge($rules, [
                        "$base" => new HasAtleastOneConditionValidation($question["conditions"], $question["hasConditions"]),
                        "$base.ask_if_relation" => "required|string|in:all,any",
                        "$base.skip_to_relation" => "required|string|in:all,any",
                        "$baseAskIf.skipToQuestionId" => "nullable",
                    ]);

                    // Specific Rules For Ask If Conditions
                    $rules = array_merge($rules, $this->generateRulesForConditions($question["conditions"]["ask_if"], $question, $sectionIndex, $questionIndex, $this->allQuestions));

                    // Specific Rules For Skip To Conditions
                    $rules = array_merge($rules, $this->generateRulesForConditions($question["conditions"]["skip_to"], $question, $sectionIndex, $questionIndex, $this->allQuestions));
                }
            }
        }
        return $rules;
    }

    private function generateRulesForConditions($conditions, $question, $sectionIndex, $questionIndex, $allQuestions): array
    {

        $rules = [];
        foreach ($conditions as $index => $condition)
        {
            $indexedCondition = "sections.$sectionIndex.questions.$questionIndex.conditions.$condition[type].$index";
            $parentQuestion = $allQuestions->where("id", $condition["parentQuestionId"])->first();
            $skipToQuestion = $allQuestions->where("id", $condition["skipToQuestionId"])->first();
            $parentQuestionType = $parentQuestion["type"];
            $comparisonOperatorRule = "required|";
            $requiredAnswerIdRule = "nullable";
            if ($parentQuestionType == "1" || $parentQuestionType == "2" || $parentQuestionType == "7")
            {
                $requiredAnswerIdCollection = implode(",", collect($parentQuestion["options"])->pluck("id")->toArray());
                $comparisonOperatorRule .= "in:is_selected,is_not_selected";
                $requiredAnswerIdRule = "required|uuid|in:$requiredAnswerIdCollection";
            }
            else
            {
                $comparisonOperatorRule .= "in:less_than,less_than_equal_to,equal_to,more_than_equal_to,more_than,between";
            }
            $requiredAnswerValRule = "nullable";
            $requiredMinAnswerIDRule = "nullable";
            $requiredAnswerMinValRule = "nullable";
            $requiredMaxAnswerIDRule = "nullable";
            $requiredAnswerMaxValRule = "nullable";
            $parentQuestionIdRule = $skipToQuestionIdRule = null;
            if ($condition["type"] == "ask_if")
            {
                $parentQuestionIdRule = ["required", "uuid", new AskIfParentQuestionIsBeforeValidation($parentQuestion["questionNumber"], $question["questionNumber"])];
                $skipToQuestionIdRule = "nullable";
            }
            if ($condition["type"] == "skip_to")
            {
                $parentQuestionIdRule = "required|in:" . $question["id"];
                $skipToQuestionIdRule = ["required", "uuid", new SkipToQuestionAfterValidation($skipToQuestion["questionNumber"], $question["questionNumber"])];
            }
            if ($condition["comparisonOperator"] !== "between")
            {
                $requiredAnswerValRule = "required";
            }
            elseif ($condition["comparisonOperator"] == "between" && (!in_array($parentQuestion["typeText"], ["nps", "rating"])))
            {
                $requiredAnswerIdCollection = implode(",", collect($parentQuestion["options"])->pluck("id")->toArray());
                $requiredMinAnswerIDRule = $requiredMaxAnswerIDRule = "required|uuid|in:$requiredAnswerIdCollection";
                $requiredAnswerMinValRule = $requiredAnswerMaxValRule = "required";
            }
            $rules = array_merge($rules, [
                "$indexedCondition.id" => "required|uuid",
                "$indexedCondition.questionId" => "required|in:" . $question["id"],
                "$indexedCondition.parentQuestionId" => $parentQuestionIdRule,
                "$indexedCondition.comparisonOperator" => $comparisonOperatorRule,
                "$indexedCondition.requiredAnswerId" => $requiredAnswerIdRule,
                "$indexedCondition.requiredAnswerVal" => "$requiredAnswerValRule",
                "$indexedCondition.requiredMinAnswerId" => "$requiredMinAnswerIDRule",
                "$indexedCondition.requiredAnswerMinVal" => "$requiredAnswerMinValRule",
                "$indexedCondition.requiredMaxAnswerId" => "$requiredMaxAnswerIDRule",
                "$indexedCondition.requiredAnswerMaxVal" => "$requiredAnswerMaxValRule",
                "$indexedCondition.isSubQuestion" => "required|boolean",
                "$indexedCondition.skipToQuestionId" => $skipToQuestionIdRule,
            ]);
        }
        return $rules;
    }

    public function messages(): array
    {
        $messages =  [
            "id.required" => "Survey ID is required",
            "id.uuid" => "Survey ID is not a valid UUID",
            "title.required" => "Survey title is required",
            "title.string" => "Survey title must be a string",
            "subTitle.required" => "Survey Subtitle is required",
            "subTitle.string" => "Survey Subtitle must be a string",
            "description.string" => "Survey description must be a string",
            "userId.required" => "User ID is required",
            "userId.integer" => "User ID is invalid",
            "userId.exists" => "User with provided ID does not exist",
            "organisationId.required" => "Please Select An Organisation",
            "organisationId.integer" => "Organisation ID is invalid",
            "organisationId.exists" => "Organisation with provided ID does not exist",
            "note.string" => "Note Must Be A String",
            "isPersonalInfoQuestionAsked.required" => "isPersonalInfoQuestionAsked is required",
            "isPersonalInfoQuestionAsked.boolean" => "isPersonalInfoQuestionAsked should be a boolean value",

            // Sections
            // "sections" => "min:1",
            "sections.*.id.required" => "ID for Section |:attribute| is required",
            "sections.*.id.uuid" => "ID for Section |:attribute| is not a valid UUID",
            "sections.*.title.required" => "Title for Section |:attribute| is required",
            "sections.*.title.string" => "Title for Section |:attribute| must be a string",
            "sections.*.position.required" => "Position for Section |:attribute| is required",
            "sections.*.position.integer" => "Position for Section |:attribute| must be an integer",
            "sections.*.position.gt" => "Position for Section |:attribute| must be greater than 0",

            // Questions
            "sections.*.questions.min" => "There needs to be minimum 1 Question in | :attribute",
            "sections..questions..id.required" => "Question ID is required for |:attribute",
            "sections..questions..id.uuid" => "Question ID for |:attribute| must be a UUID",
            "sections..questions..questionNumber.required" => "Question Number for |:attribute| is required",
            "sections..questions..questionNumber.integer" => "Question Number for |:attribute| must be an integer",
            "sections..questions..questionNumber.gt" => "Question Number for |:attribute| must be greater than 0",
            "sections..questions..type.required" => "Question Type for |:attribute| is required",
            "sections..questions..type.exists" => "Question Type for |:attribute| does not exists in database",
            "sections..questions..statement.required" => "Question Statement for |:attribute| is required ",
            "sections..questions..statement.string" => "Question Statement for |:attribute| must be a string ",
            "sections..questions..subTitle.required" => "Question Subtitle for |:attribute| is required ",
            "sections..questions..subTitle.string" => "Question Subtitle for |:attribute| must be a string",
            "sections..questions..uniqueAttributeId.exists" => "Unique Attribute for |:attribute| does not exists",
            "sections..questions..required.required_unless" => "'Required' value for |:attribute| is required",
            "sections..questions..required.boolean" => "'Required' value for |:attribute| must be a boolean value",
            "sections..questions..comment.required_unless" => "Comment value for |:attribute| is required",
            "sections..questions..comment.boolean" => "Comment value for |:attribute| must be a boolean value",
            "sections..questions..image.required_unless" => "Image value for |:attribute| is required",
            "sections..questions..image.boolean" => "Image value for |:attribute| must be a boolean value",
            "sections..questions..hasConditions.required_unless" => "'Has Conditions' value for |:attribute| is required",
            "sections..questions..hasConditions.boolean" => "'Has Conditions' value for |:attribute| must be a boolean value",

            // Single and Multiple Questions (Other & Options (Options Validation for Likert And Matrix as well))
            "sections..questions..other.required_if" => "Other Option Value is required for |:attribute",
            "sections..questions..boolean" => "'Other Option' value for |:attribute| must be a boolean value",
            "sections..questions..options.required_if" => "Options are required for |:attribute",
            "sections..questions..options.array" => "Options for |:attribute| must be an array",
            "sections..questions..options.min" => "A minimum of 2 options are required for  |:attribute",
            "sections..questions..options.*.id.required_if" => "Option ID of |:attribute| is required",
            "sections..questions..options.*.id.uuid" => "Option ID of |:attribute| must be a UUID",
            "sections..questions..options.*.position.required_if" => "Option Value of |:attribute| is required",
            "sections..questions..options.*.position.integer" => "Option Position of |:attribute| must be an string",
            "sections..questions..options.*.value.required_if" => "Option Value of |:attribute| is required",
            "sections..questions..options.*.value.string" => "Option Value of |:attribute| must be an string",
            "sections..questions..options.*.score.required_if" => "Option Score of |:attribute| is required",
            "sections..questions..options.*.score.integer" => "Option Score of |:attribute| must be an integer",
            "sections..questions..options.*.comment.string" => "Option Comment of |:attribute| must be a string",

            // NPS / Rating Questions
            "sections..questions..maxRating.required_if" => "Value of Max Rating for |:attribute| is required",
            "sections..questions..maxRating.integer" => "Value of Max Rating for |:attribute| must be an integer",
            "sections..questions..maxRating.min" => "Value of Max Rating for |:attribute| should be Minimum 5",
            "sections..questions..maxRating.max" => "Value of Max Rating for |:attribute| should be Maximum 10",
            "sections..questions..lowText.required_if" => "Value of 'Low Text' for |:attribute| is required",
            "sections..questions..lowText.string" => "Value of 'Low Text' for |:attribute| must be a string",
            "sections..questions..midText.required_if" => "Value of 'Mid Text' for |:attribute| is required",
            "sections..questions..midText.string" => "Value of 'Mid Text' for |:attribute| must be a string",
            "sections..questions..highText.required_if" => "Value of 'High Text' for |:attribute| is required",
            "sections..questions..highText.string" => "Value of 'High Text' for |:attribute| must be a string",

            // Matrix / Likert
            "sections..questions..subQuestions.required_if" => "SubQuestions are required in |:attribute",
            "sections..questions..subQuestions.array" => "SubQuestion in |:attribute| must be an array",
            "sections..questions..subQuestions.min" => "There should be a minimum of 2 SubQuestions in |:attribute",
            "sections..questions..subQuestions.*.id.required_if" => "Id for SubQuestion in |:attribute| is required",
            "sections..questions..subQuestions.*.id.uuid" => "Id for SubQuestion in |:attribute| must be a valid UUID",
            "sections..questions..subQuestions.*.position.required_if" => "SubQuestion Number is required in |:attribute",
            "sections..questions..subQuestions.*.position.integer" => "SubQuestion Number in |:attribute| must be an integer",
            "sections..questions..subQuestions.*.statement.required_if" => "The Question Statement for |:attribute| is required",
            "sections..questions..subQuestions.*.statement.string" => "The Question Statement for the SubQuestion in |:attribute| must be a string",
            "sections..questions..subQuestions.*.type.required_if" => "The type for the SubQuestion in |:attribute| is required",
            "sections..questions..subQuestions.*.type.in" => "The type for |:attribute| must be 'subQuestion'",
            "sections..questions..subQuestions.*.comment.required_if" => "The comment value for |:attribute| is required",
            "sections..questions..subQuestions.*.comment.boolean" => "The comment value for |:attribute| must be a boolean value",
            "sections..questions..subQuestions.*.uniqueAttribute.exists" => "The unique attribute for the SubQuestion in |:attribute| not found in database",
            "sections..questions..optionsCount.required_if" => "The options count for |:attribute| is required",
            "sections..questions..optionsCount.integer" => "The options count for |:attribute| must be an integer",
            "sections..questions..optionsCount.min" => "There must be at least 2 options in |:attribute",
        ];

        // Messages For Condition Validations
        foreach ($this->sections as $sectionIndex => $section)
        {
            foreach ($section["questions"] as $questionIndex => $question)
            {
                $base = "sections.$sectionIndex.questions.$questionIndex.conditions";
                if ($question["hasConditions"])
                {
                    // Base Rules For Both Type Of Conditions
                    $messages = array_merge($messages, [
                        "$base.ask_if_relation.required" => "'Ask If' relation for |:attribute| is required",
                        "$base.ask_if_relation.string" => "The 'Ask If' relation for |:attribute| must be a string",
                        "$base.ask_if_relation.in" => "The 'Ask If' relation for |:attribute| must be either 'all' or 'any'",
                        "$base.skip_to_relation.required" => "'Skip To' relation for |:attribute| is required",
                        "$base.skip_to_relation.string" => "The 'Skip To' relation for |:attribute| must be a string",
                        "$base.skip_to_relation.in" => "The 'Skip To' relation for |:attribute| must be either 'all' or 'any'",
                    ]);

                    // Specific Rules For Ask If Conditions
                    $messages = array_merge($messages, $this->generateMessagesForConditions($question["conditions"]["ask_if"], $sectionIndex, $questionIndex, $this->allQuestions));

                    // Specific Rules For Skip To Conditions
                    $messages = array_merge($messages, $this->generateMessagesForConditions($question["conditions"]["skip_to"], $sectionIndex, $questionIndex, $this->allQuestions));
                }
            }
        }
        return $messages;
    }

    private function generateMessagesForConditions($conditions, $sectionIndex, $questionIndex, $allQuestions): array
    {
        $messages = [];
        foreach ($conditions as $index => $condition)
        {
            $indexedCondition = "sections.$sectionIndex.questions.$questionIndex.conditions.$condition[type].$index";
            $parentQuestion = $allQuestions->where("id", $condition["parentQuestionId"])->first();
            $parentQuestionType = $parentQuestion["type"];
            if ($parentQuestionType == "1" || $parentQuestionType == "2" || $parentQuestionType == "7")
            {
                $comparisonOperatorInRuleMessage = "The Comparison Operator for |:attribute| must be either 'Is Selected' or 'Is Not Selected'";
            }
            else
            {
                $comparisonOperatorInRuleMessage = "The Comparison Operator for |:attribute| must be one of the following: Less Than, Less Than Equal To, Equal To, More Than Equal To, More Than, Between";
            }
            $messages = array_merge($messages, [
                "$indexedCondition.id.required" => "The Condition ID for |:attribute| is required.",
                "$indexedCondition.id.uuid" => "The Condition ID for |:attribute| must be a valid UUID.",
                "$indexedCondition.questionId.required" => "The Question ID for |:attribute| is required.",
                "$indexedCondition.questionId.in" => "The Question ID for |:attribute| should be the same as the Question the condition is applied on.",
                "$indexedCondition.parentQuestionId.required" => "The Parent Question ID for |:attribute| is required.",
                "$indexedCondition.parentQuestionId.uuid" => "The Parent Question ID for |:attribute| must be a valid UUID.",
                "$indexedCondition.parentQuestionId.in" => "The Parent Question ID for |:attribute| should be the same as the Question the condition is applied on.",
                "$indexedCondition.comparisonOperator.required" => "The Comparison Operator for |:attribute| is required.",
                "$indexedCondition.comparisonOperator.in" => $comparisonOperatorInRuleMessage,
                "$indexedCondition.requiredAnswerId.required" => "The 'Required Answer ID' for |:attribute| is required.",
                "$indexedCondition.requiredAnswerId.uuid" => "The 'Required Answer ID' for |:attribute| must be a valid UUID.",
                "$indexedCondition.requiredAnswerId.in" => "The 'Required Answer ID' for |:attribute| must be one of the valid options for the parent Question.",
                "$indexedCondition.requiredAnswerVal.required" => "The 'Required Answer Value' for |:attribute| is required.",
                "$indexedCondition.requiredMinAnswerId.required" => "The Value for 'Required Min Answer ID' for |:attribute| is required",
                "$indexedCondition.requiredMinAnswerId.uuid" => "The Value for 'Required Min Answer ID' for  |:attribute| must be a valid UUID",
                "$indexedCondition.requiredMinAnswerId.in" => "The Value for 'Required Min Answer ID' for |:attribute| must be one of the valid options for the parent Question.",
                "$indexedCondition.requiredAnswerMinVal.required" => "The Value of 'Required Answer Min Val' for |:attribute| is required.",
                "$indexedCondition.requiredMaxAnswerId.required" => "The Value for 'Required Max Answer ID' for |:attribute| is required",
                "$indexedCondition.requiredMaxAnswerId.uuid" => "The Value for 'Required Max Answer ID' for  |:attribute| must be a valid UUID",
                "$indexedCondition.requiredMaxAnswerId.in" => "The Value for 'Required Max Answer ID' |:attribute| must be one of the valid options for the parent Question.",
                "$indexedCondition.requiredAnswerMaxVal.required" => "The Value of 'Required Answer Max Val' for |:attribute| is required.",
                "$indexedCondition.isSubQuestion" => "required|boolean",
                "$indexedCondition.skipToQuestionId.required" => "The 'Skip To Question ID' for |:attribute| is required.",
                "$indexedCondition.skipToQuestionId.uuid" => "The 'Skip To Question ID' for |:attribute| must be a valid UUID.",
            ]);
        }
        return $messages;
    }
}
